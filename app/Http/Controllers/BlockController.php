<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlockUserRequest;
use App\Models\Block;
use App\Http\Requests\StoreBlockRequest;
use App\Http\Requests\UnblockUserRequest;
use App\Http\Requests\UpdateBlockRequest;
use App\Services\BlockService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class BlockController extends Controller
{
    use ApiResponse;

    protected BlockService $blockService;

    public function __construct(BlockService $blockService)
    {
        $this->blockService = $blockService;
    }

    public function block(BlockUserRequest $request)
    {
        $result = $this->blockService->blockUser($request->blocked_id);

        if (!$result['status']) {
            return $this->errorResponse($result['message'], 409);
        }

        return $this->successResponse($result['message']);
    }

    public function unblock(UnblockUserRequest $request)
    {
        $this->blockService->unblockUser($request->blocked_id);
        return $this->successResponse('User unblocked');
    }

    public function blockedUsers()
    {
        $blocked = $this->blockService->getBlockedUsers();
        return $this->successResponse('Blocked users retrieved successfully', $blocked);
    }
}