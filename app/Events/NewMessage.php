<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Message;

class NewMessage implements ShouldBroadcastNow
{ 
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        //dd($message);
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //chatroom_4_2
       return new Channel('chatroom.'.$this->message->from_user.'.'.$this->message->to_user);
  
    }
     public function broadcastWith()
    {
     return [
         'content'=>$this->message->content,
         'created_at'=>$this->message->created_at,
         'from_user'=>$this->message->from_user,
         'to_user'=>$this->message->to_user,
     ];
    }
}