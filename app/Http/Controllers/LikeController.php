<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Http\Requests\StoreLikeRequest;
use App\Http\Requests\UpdateLikeRequest;
use App\Services\LikeService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

    class LikeController extends Controller
    {
        use ApiResponse;
        public function __construct(private LikeService $likeService) {}

        public function sendLike(StoreLikeRequest $request)
        {
            $like = $this->likeService->sendLike(Auth::id(), $request->receiver_id);
            return $this->successResponse('Like sent', $like, 201);
        }

        public function respond($likeId, StoreLikeRequest $request)
        {
            $result = $this->likeService->respondToLike($likeId, $request->response);

            if ($result === null) {
                return $this->successResponse('Like rejected and deleted');
            }

            if ($result === 'accepted') {
                return $this->successResponse('Friendship created');
            }

            return $this->successResponse('Like still pending', $result);
        }
        public function pending()
        {
            $likes = $this->likeService->getPendingLikesForUser(Auth::id());
            return $this->successResponse('Pending likes', $likes);
        }


    }

