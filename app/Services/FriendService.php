<?php

namespace App\Services;

use App\Models\Block;
use App\Models\Friend;

    /**
     * Create a new class instance.
     */
    class FriendService
    {
        public function getAcceptedFriends($userId)
        {
            $blockedUserIds = Block::where('blocker_id', $userId)->pluck('blocked_id')->toArray();
            $blockedMeIds   = Block::where('blocked_id', $userId)->pluck('blocker_id')->toArray();

            return Friend::where('user_id', $userId)
                         ->whereNotIn('friend_id', $blockedUserIds)
                         ->whereNotIn('friend_id', $blockedMeIds)
                         ->with('friend')
                         ->get()
                         ->pluck('friend');
        }

        public function getAcceptedFriendIds($userId)
        {
            $blockedUserIds = Block::where('blocker_id', $userId)->pluck('blocked_id')->toArray();
            $blockedMeIds   = Block::where('blocked_id', $userId)->pluck('blocker_id')->toArray();

            return Friend::where('user_id', $userId)
                         ->whereNotIn('friend_id', $blockedUserIds)
                         ->whereNotIn('friend_id', $blockedMeIds)
                         ->pluck('friend_id')
                         ->toArray();
        }
    }
