<?php

namespace App\Services;

use App\Models\Friend;

    /**
     * Create a new class instance.
     */
    class FriendService
    {
        public function getAcceptedFriends($userId)
        {
            return Friend::where('user_id', $userId)

                         ->with('friend') // تأكد إنو عامل العلاقة في الموديل
                         ->get()
                         ->pluck('friend');
        }

        public function getAcceptedFriendIds($userId)
        {
            return Friend::where('user_id', $userId)

                         ->pluck('friend_id')
                         ->toArray();
        }
    }

