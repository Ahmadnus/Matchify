<?php

namespace App\Observers;

use App\Enum\NotificationType;
use App\Models\Chat;
use App\Models\Friend;
use App\Models\Like;
use App\Models\User;
use App\Services\NotificationService;

    class LikeObserver
{


public function updated(Like $like)
{
    if ($like->status === 'accepted') {
        // تأكد من عدم وجود علاقة صداقة مسبقة
        $exists = Friend::where(function ($q) use ($like) {
            $q->where('user_id', $like->sender_id)
              ->where('friend_id', $like->receiver_id);
        })->orWhere(function ($q) use ($like) {
            $q->where('user_id', $like->receiver_id)
              ->where('friend_id', $like->sender_id);
        })->exists();

        if (!$exists) {
            Friend::create([
                'user_id' => $like->sender_id,
                'friend_id' => $like->receiver_id,
            ]);
            Friend::create([
                'user_id' => $like->receiver_id,
                'friend_id' => $like->sender_id,
            ]);
        }

        // تحقق من عدم وجود محادثة مسبقة
        $chatExists = Chat::where(function ($q) use ($like) {
            $q->where('user_one_id', $like->sender_id)
              ->where('user_two_id', $like->receiver_id);
        })->orWhere(function ($q) use ($like) {
            $q->where('user_one_id', $like->receiver_id)
              ->where('user_two_id', $like->sender_id);
        })->exists();

        if (!$chatExists) {
            Chat::create([
                'user_one_id' => $like->sender_id,
                'user_two_id' => $like->receiver_id,
            ]);
        }

        // إرسال إشعار للطرفين
        $sender   = User::find($like->sender_id);
        $receiver = User::find($like->receiver_id);
        $notif    = app(NotificationService::class);

        // إشعار للراسل
        $notif->sendToUser(
            recipient: $sender,
            title: 'It\'s a Match!',
            body: "You and {$receiver->name} liked each other!",
            data: [
                'like_id'     => $like->id,
                'friend_id'   => $receiver->id,
            ],
            type: NotificationType::NEW_MATCH,
            sender: $receiver
        );

        // إشعار للمستلم
        $notif->sendToUser(
            recipient: $receiver,
            title: 'It\'s a Match!',
            body: "{$sender->name} liked you back!",
            data: [
                'like_id'     => $like->id,
                'friend_id'   => $sender->id,
            ],
            type: NotificationType::NEW_MATCH,
            sender: $sender
        );

        $like->delete();
    }

    if ($like->status === 'rejected') {
        $sender = User::find($like->sender_id);
        $receiver = User::find($like->receiver_id);

        app(NotificationService::class)->sendToUser(
            recipient: $sender,
            title: 'Like Rejected',
            body: "{$receiver->name} rejected your like request.",
            data: [
                'like_id' => $like->id,
            ],
            type: NotificationType::DATE_REQUEST_DECLINED,
            sender: $receiver
        );

        $like->delete();
    }
}}