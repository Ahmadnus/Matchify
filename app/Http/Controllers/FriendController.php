<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Friend;
use App\Http\Requests\StoreFriendRequest;
use App\Http\Requests\UpdateFriendRequest;
use App\Services\FriendService;
use App\Traits\ApiResponse;

class FriendController extends Controller
{use ApiResponse;
    protected $friendService;

    public function __construct(FriendService $friendService)
    {
        $this->friendService = $friendService;
    }



    public function getAcceptedFriends(Request $request)
    {
        $userId = $request->user()->id;
        $friends = $this->friendService->getAcceptedFriends($userId);

        return $this->successResponse('Accepted friends retrieved successfully.', $friends);
    }

    public function getAcceptedFriendIds(Request $request)
    {
        $userId = $request->user()->id;
        $friendIds = $this->friendService->getAcceptedFriendIds($userId);

        return $this->successResponse('Accepted friend IDs retrieved successfully.', $friendIds);
    }

}