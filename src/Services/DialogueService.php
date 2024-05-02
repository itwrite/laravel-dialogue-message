<?php

namespace Itwri\DialogueMessageService\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Itwri\DialogueMessageService\Enums\DialogueChannelEnum;
use Itwri\DialogueMessageService\Events\NewDialogueCreated;
use Itwri\DialogueMessageService\Models\Dialogue;
use Itwri\DialogueMessageService\Models\DialogueMember;

class DialogueService
{

    /**
     * -------------------------------------------
     * 注：users 应包含owner
     * -------------------------------------------
     * @param $createUser
     * @param $others
     * @param $params
     * @return Builder|Model|null
     * @throws \Exception
     * itwri 2024/4/23 11:25
     */
    public function create($createUser, $others, $name, $channel=DialogueChannelEnum::NORMAL, $params = [])
    {
        if(count($others) < 1 || !$createUser){
            return null;
        }

        $peopleCount = count($others) + 1; //拉起对话的人 与 聊天目标用户数

        if($peopleCount == 2){ //一对一的对话情况，先检索数据库是否已有对话，有则返回已有的对话
            $dialogue = Dialogue::query()->where(['member_count'=>$peopleCount,'channel'=>$channel])->whereHas('members',function (Builder $builder) use($createUser,$others){
                return $builder->whereIn('user_id',[$createUser->id,$others[0]->id]);
            },'=',$peopleCount)->first();
            if($dialogue){
                return $dialogue;
            }
        }

        $datetime = date('Y-m-d H:i:s');

        $params = array_merge(array_merge(['channel'=>$channel,'name'=>$name],$params),[
            'create_user_id'=>$createUser->id,
            'member_count'=>$peopleCount, //创建者 + 其他人的数量
            'created_at'=>$datetime,
            'updated_at'=>$datetime
        ]);

        DB::beginTransaction();
        try {

            /**
             * @var Dialogue $dialogue
             */
            $dialogue = Dialogue::query()->create($params);
            if(empty($dialogue)){
                throw new \Exception('对话创建失败');
            }

            $members = [
                [
                    'dialogue_id'=>$dialogue->id,
                    'user_id'=>$createUser->id,
                    'created_at'=>$datetime,
                    'updated_at'=>$datetime
                ]
            ];
            //会话成员
            foreach ($others as $user) {
                $members[] = [
                    'dialogue_id'=>$dialogue->id,
                    'user_id'=>$user->id,
                    'created_at'=>$datetime,
                    'updated_at'=>$datetime
                ];
            }

            DialogueMember::query()->insert($members);

            DB::commit();

        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
        //创建新会话成功
        event(new NewDialogueCreated($dialogue));

        $this->updateMemberCount($dialogue);

        return $dialogue;
    }

    /**
     * -------------------------------------------
     * -------------------------------------------
     * @param Dialogue $dialogue
     * @return bool
     * itwri 2024/4/23 17:23
     */
    public function updateMemberCount(Dialogue $dialogue)
    {
        return DB::table('dialogues')->where(['id'=>$dialogue->id])->update([
            'member_count'=>DB::raw("(SELECT COUNT(DISTINCT dialogue_members.user_id) FROM dialogue_members WHERE dialogue_members.dialogue_id = dialogues.id)"),
        ]);
    }
}
