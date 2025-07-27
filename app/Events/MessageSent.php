<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;


class MessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels,InteractsWithSockets;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load('sender');

    }

    public function broadcastOn(): array
    {
        return [
            new Channel('chat.'. $this->message->chat_id),
        ];
    }
    public function broadcastWith()
    {
        return [
            'id'        => $this->message->id,
            'message'   => $this->message->message,
            'sender'    => [
                'id'   => $this->message->sender->id,
                'name' => $this->message->sender->name,
            ],
            'sent_at'   => $this->message->created_at->toDateTimeString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}