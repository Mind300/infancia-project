<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $receiver;
    public string $message;

    public function __construct(User $receiver, string $message)
    {
        $this->receiver = $receiver;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        $senderId = auth()->user()->id;
        $receiverId = $this->receiver->id;

        // Create a unique channel name using the user IDs in ascending order
        $channelName = 'chat.' . min($senderId, $receiverId) . '.' . max($senderId, $receiverId);

        return new Channel($channelName);
    }

    /**
     * Get the event name the event should broadcast as.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'chatMessage';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        // return contentResponse($this->message, 'Messages Fetch Successfully');
        return [
            'content' => $this->message,
        ];
    }
}