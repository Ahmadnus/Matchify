<?php

namespace App\Observers;

use App\Models\Friend;
use App\Models\Like;


    class LikeObserver
{
    public function updated(Like $like)
    {
        if ($like->status === 'accepted') {

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

            $like->delete();
        }

        if ($like->status === 'rejected') {
            $like->delete();
        }
    }

}
