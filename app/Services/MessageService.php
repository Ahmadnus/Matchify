<?php

// app/Services/MessageService.php
namespace App\Services;

use App\Enum\NotificationType;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class MessageService
{
    public function sendMessage(Chat $chat, string $content): Message
    {
        $sender = Auth::user();

        // تحقق من أن المستخدم جزء من الشات
        if ($chat->user_one_id !== $sender->id && $chat->user_two_id !== $sender->id) {
            abort(403, 'Unauthorized');
        }

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $sender->id,
            'message' => $content,
        ]);

        $receiverId = ($chat->user_one_id === $sender->id) ? $chat->user_two_id : $chat->user_one_id;
        $receiver = User::findOrFail($receiverId);

        app(NotificationService::class)->sendToUser(
            recipient: $receiver,
            title: trans('New Message'),
            body: trans(':name sent you a message', ['name' => $sender->name]),
            data: [
                'chat_id'    => $chat->id,
                'message_id' => $message->id,
            ],
            type: NotificationType::NEW_MESSAGE,
            sender: $sender
        );

        broadcast(new MessageSent($message))->toOthers();

        return $message;
    }

    public function getMessagesForChat(Chat $chat): array
    {
        $user = Auth::user();

        if ($chat->user_one_id !== $user->id && $chat->user_two_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $chat->load(['messages.sender']);

        return [
            'chat' => $chat,
            'messages' => $chat->messages,
        ];
    }

    public function getAllUserChats(): \Illuminate\Support\Collection
    {
        $user = Auth::user();

        $chatsAsUserOne = $user->chatsAsUserOne()->with('userTwo')->get();
        $chatsAsUserTwo = $user->chatsAsUserTwo()->with('userOne')->get();

        return $chatsAsUserOne->merge($chatsAsUserTwo);
    }
}
