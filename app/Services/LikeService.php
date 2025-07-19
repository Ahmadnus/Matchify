<?php

namespace App\Services;

use App\Models\Friend;
use App\Models\Like;

class LikeService
{
    /**
     * Create a new class instance.
     */
    public function sendLike(int $senderId, int $receiverId): Like
    {
        return Like::firstOrCreate(
            ['sender_id' => $senderId, 'receiver_id' => $receiverId],
            ['status' => 'pending']
        );
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
