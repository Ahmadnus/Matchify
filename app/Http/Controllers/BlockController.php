<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Http\Requests\StoreBlockRequest;
use App\Http\Requests\UpdateBlockRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{

    public function block(Request $request)
    {
        $request->validate([
            'blocked_id' => 'required|exists:users,id|not_in:' . Auth::id(),
        ]);

        $alreadyBlocked = Block::where('blocker_id', Auth::id())
            ->where('blocked_id', $request->blocked_id)
            ->exists();

        if ($alreadyBlocked) {
            return response()->json(['message' => 'User already blocked'], 409);
        }

        Block::create([
            'blocker_id' => Auth::id(),
            'blocked_id' => $request->blocked_id,
        ]);

        return response()->json(['message' => 'User blocked successfully']);
    }

    public function unblock(Request $request)
    {
        $request->validate([
            'blocked_id' => 'required|exists:users,id',
        ]);

        Block::where('blocker_id', Auth::id())
            ->where('blocked_id', $request->blocked_id)
            ->delete();

        return response()->json(['message' => 'User unblocked']);
    }

    public function blockedUsers()
    {
        $blocked = Auth::user()->blockedUsers()->with('blocked')->get();
        return response()->json([
            'data' => $blocked->pluck('blocked'),
        ]);
    }
}
