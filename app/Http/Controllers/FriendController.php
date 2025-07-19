<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Friend;
use App\Http\Requests\StoreFriendRequest;
use App\Http\Requests\UpdateFriendRequest;
use App\Services\FriendService;

class FriendController extends Controller
{
    protected $friendService;

    public function __construct(FriendService $friendService)
    {
        $this->friendService = $friendService;
    }

    public function getAcceptedFriends(Request $request)
    {
        $userId = $request->user()->id;
        $friends = $this->friendService->getAcceptedFriends($userId);

        return response()->json([
            'status' => true,
            'data' => $friends,
        ]);
    }

    public function getAcceptedFriendIds(Request $request)
    {
        $userId = $request->user()->id;
        $friendIds = $this->friendService->getAcceptedFriendIds($userId);

        return response()->json([
            'status' => true,
            'data' => $friendIds,
        ]);
    }
}