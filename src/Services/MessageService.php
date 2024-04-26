<?php

namespace Itwri\DialogueMessageService\Services;

use Illuminate\Support\Facades\DB;
use Itwri\DialogueMessageService\Enums\DialogueMessageTypeEnum;
use Itwri\DialogueMessageService\Events\NewMessageCreated;
use Itwri\DialogueMessageService\Models\DialogueMember;
use Itwri\DialogueMessageService\Models\Dialogue;
use Itwri\DialogueMessageService\Models\DialogueMessage;
use Itwri\DialogueMessageService\Models\DialogueMessageStatus;
use Itwri\DialogueMessageService\Models\DialogueStatus;

class MessageService
{
    /**
     * -------------------------------------------
     * Someone send a message to a dialogue.
     * -------------------------------------------
     * @param DialogueMember $sender
     * @param $message
     * @param Dialogue $dialogue
     * @return DialogueMessage|null
     * @throws \Exception
     * itwri 2024/4/23 15:29
     */
    public function send(DialogueMember $sender, $message, Dialogue $dialogue)
    {
        //发送人不在会话内则消息无效
        if($sender->dialogue_id != $dialogue->id){
            return null;
        }
        $datetime = date('Y-m-d H:i:s');

        DB::beginTransaction();
        try {
            /**
             * @var DialogueMessage $dialogueMessage
             */
            $dialogueMessage = DialogueMessage::query()->create([
                'dialogue_id'=>$dialogue->id,
                'dialogue_member_id'=>$sender->id,
                'type'=>DialogueMessageTypeEnum::TEXT,
                'content'=>$message,
                'created_at'=>$datetime,
                'updated_at'=>$datetime
            ]);
            if(empty($dialogueMessage)){
                throw new \Exception('消息生成失败');
            }

            $statuses = [];
            foreach ($dialogue->members as $member) {
                $statuses[] = [
                    'dialogue_message_id'=>$dialogueMessage->id,
                    'dialogue_member_id'=>$member->id,
                    'is_read'=>$member->id == $sender->id?1:0,
                    'is_removed'=>0,
                    'created_at'=>$datetime,
                    'updated_at'=>$datetime
                ];
            }
            //生成消息对应用户的状态
            DialogueMessageStatus::query()->insert($statuses);

            //新消息会激活用户对会话的可见性, 这里可以无差别的更新，新消息对所有成员都可见
            DialogueStatus::query()->where(['dialogue_id'=>$dialogue->id])->update(['is_hidden'=>0]);

            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

        //新消息
        event(new NewMessageCreated($dialogueMessage));

        return $dialogueMessage;
    }

    /**
     * -------------------------------------------
     * 阅读消息
     * -------------------------------------------
     * @param DialogueMember $dialogueMember
     * @param DialogueMessage $dialogueMessage
     * @return bool|int
     * itwri 2024/4/26 21:39
     */
    public function read(DialogueMember $dialogueMember,DialogueMessage $dialogueMessage)
    {
        $dialogueMessageStatus = DialogueMessageStatus::query()->where(['dialogue_message_id'=>$dialogueMessage->id,'dialogue_member_id'=>$dialogueMember->id])->first();

        if(!empty($dialogueMessageStatus)){
            $dialogueMessageStatus = DialogueMessageStatus::query()->create([
                'dialogue_id'=>$dialogueMessage->dialogue_id,
                'dialogue_member_id'=>$dialogueMember->id,
                'is_read'=>1,
                'is_removed'=>0
            ]);
            if(empty($dialogueMessageStatus)){
                return false;
            }
            return true;
        }

        return $dialogueMessageStatus->update(['is_read'=>1]);
    }
}
