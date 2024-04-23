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

        if($type == DialogueTypeEnum::SINGLE && count($others) == 1){ //一对一的对话情况，先检索数据库是否已有对话，有则返回已有的对话
            $dialogue = Dialogue::query()->where(['member_count'=>2,'type'=>DialogueTypeEnum::SINGLE])->whereHas('members',function (Builder $builder) use($createUser,$others){
                return $builder->whereIn('user_id',[$createUser->id,$others[0]->id]);
            },'=',2)->first();
            if($dialogue){
                return $dialogue;
            }
        }

        $datetime = date('Y-m-d H:i:s');

        $params = array_merge(array_merge(['type'=>$type],$params),[
            'create_user_id'=>$createUser->id,
            'member_count'=>count($others) + 1, //创建者 + 其他人的数量
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
        return $dialogue->update([
            'member_count'=>DB::raw("(SELECT COUNT(DISTINCT dialogue_members.user_id) FROM dialogue_members WHERE dialogue_members.dialogue_id = dialogues.id)"),
            //单聊可以变群聊，群聊不能变单聊
            'type'=>DB::raw("if((SELECT COUNT(DISTINCT dialogue_members.user_id) FROM dialogue_members WHERE dialogue_members.dialogue_id = dialogues.id)>2,'".DialogueTypeEnum::GROUP."',type)")
        ]);
    }
}
