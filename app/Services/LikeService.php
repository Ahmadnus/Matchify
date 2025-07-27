<?php

namespace App\Services;

use App\Enum\NotificationType;
use App\Models\Friend;
use App\Models\Like;
use App\Models\User;

class LikeService
{
    /**
     * Create a new class instance.
     */
    public function sendLike(int $senderId, int $receiverId): Like
{
    // إنشاء أو جلب الإعجاب
    $like = Like::firstOrCreate(
        ['sender_id' => $senderId, 'receiver_id' => $receiverId],
        ['status' => 'pending']
    );

    // الحصول على المستخدمين
    $sender = User::findOrFail($senderId);
    $receiver = User::findOrFail($receiverId);

    // إرسال الإشعار
    app(NotificationService::class)->sendToUser(
        recipient: $receiver,
        title: trans('New Like Request'),
        body: trans(':name liked you!', ['name' => $sender->name]),
        data: [
            'like_id' => $like->id,
            'sender_id' => $senderId,
        ],
        type: NotificationType::NEW_LIKE,
        sender: $sender
    );

    return $like;
}
    public function respondToLike($likeId, string $response)
    {


            $like = Like::findOrFail($likeId);

            // تغيّر الحالة فقط، والـ Observer يتولى الباقي
            $like->status = $response;
            $like->save();

            return $like;
        }
    public function getPendingLikesForUser(int $userId)
    {
        return Like::where('receiver_id', $userId)
                   ->where('status', 'pending')
                   ->with('sender')
                   ->get();
    }

    public function getAcceptedLikes(int $userId)
    {
        return Like::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
        })->where('status', 'accepted')->get();
    }

}
