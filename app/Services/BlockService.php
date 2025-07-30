<?php
namespace App\Services;

use App\Models\Block;
use Illuminate\Support\Facades\Auth;

class BlockService
{
    public function blockUser(int $blockedId): array
    {
        $alreadyBlocked = Block::where('blocker_id', Auth::id())
            ->where('blocked_id', $blockedId)
            ->exists();

        if ($alreadyBlocked) {
            return ['status' => false, 'message' => 'User already blocked'];
        }

        Block::create([
            'blocker_id' => Auth::id(),
            'blocked_id' => $blockedId,
        ]);

        return ['status' => true, 'message' => 'User blocked successfully'];
    }

    public function unblockUser(int $blockedId): void
    {
        Block::where('blocker_id', Auth::id())
            ->where('blocked_id', $blockedId)
            ->delete();
    }

    public function getBlockedUsers()
    {
        return Auth::user()->blockedUsers()->with('blocked')->get()->pluck('blocked');
    }
}
