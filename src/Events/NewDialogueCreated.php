<?php

namespace Itwri\DialogueMessageService\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Itwri\DialogueMessageService\Models\Dialogue;

class NewDialogueCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Dialogue
     */
    public Dialogue $dialogue;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Dialogue $dialogue)
    {
        //
        $this->dialogue = $dialogue;
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
        $channels = [];
        foreach ($this->dialogue->members as $member) {
            $channels[] =  new PrivateChannel('dialogue.new-'.$member->user_id);
        }
        return $channels;
    }
}
