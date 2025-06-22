<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $receiver_id;
    public $date_time;
    public $sender_id;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $sender_id, $receiver_id, $date_time)
    {
        $this->message = $message;
        $this->receiver_id = $receiver_id;
        $this->date_time = $date_time;
        $this->sender_id = $sender_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('message.' . $this->receiver_id),
        ];
    }
    function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'receiver_id' => $this->receiver_id,
            'sender_id' => $this->sender_id,
            'created_at' => $this->date_time,
        ];
    }
}
