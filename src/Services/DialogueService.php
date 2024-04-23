<?php

namespace Itwri\DialogueMessageService\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Itwri\DialogueMessageService\Enums\DialogueTypeEnum;
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
    public function create($createUser,$others,$params = [])
    {
        if(count($others) < 1 || !$createUser){
            return null;
        }
        $type = DialogueTypeEnum::SINGLE;
        //一对一聊天
        if(count($others) > 1){
            $type = DialogueTypeEnum::GROUP;
        }

        $datetime = date('Y-m-d H:i:s');

        $params = array_merge($params,[
            'type'=>$type,
            'create_user_id'=>$createUser->id,
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
        return $dialogue->update(['member_count'=>DB::raw("(SELECT COUNT(DISTINCT dialogue_members.user_id) FROM dialogue_members WHERE dialogue_members.dialogue_id = dialogues.id)")]);
    }
}
