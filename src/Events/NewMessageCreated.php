<?php

namespace Itwri\DialogueMessageService\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Itwri\DialogueMessageService\Models\DialogueMessage;

class NewMessageCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var DialogueMessage
     */
    public DialogueMessage $dialogueMessage;

    public function __construct(DialogueMessage $dialogueMessage)
    {
        /**
         * 这段代码是有用的，加载模型关系with的数据
         */
        if($dialogueMessage->dialogueMember){
            $dialogueMessage->dialogueMember->user;
        }

        $this->dialogueMessage = $dialogueMessage;
    }

    /**
     * -------------------------------------------
     * Get the channels the event should broadcast on.
     * -------------------------------------------
     * @return array
     * itwri 2024/4/23 11:46
     */
    public function broadcastOn()
    {
        $members = $this->dialogueMessage->dialogue->members;
        $channels = [];
        foreach ($members as $member) {
            $channels[] =  new PrivateChannel('message.new-'.$member->user_id);
        }
        return $channels;
    }
}
