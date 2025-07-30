<?php

namespace App\Http\Controllers;

use App\Models\UserAnswer;
use App\Http\Requests\StoreUserAnswerRequest;
use App\Http\Requests\UpdateUserAnswerRequest;
use App\Services\UserAnswerService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;

class UserAnswerController extends Controller
{
    use ApiResponse;

    protected $userAnswerService;

    public function __construct(UserAnswerService $userAnswerService)
    {
        $this->userAnswerService = $userAnswerService;
    }


    public function show($id)
    {
        try {
            $data = $this->userAnswerService->getById($id);
            return $this->successResponse('Answer fetched successfully', $data);
        } catch (\Throwable $e) {
            Log::error($e);
            return $this->errorResponse('Failed to fetch answer', 500);
        }
    }

    public function store(StoreUserAnswerRequest $request)
    {
        try {
            $data = $this->userAnswerService->create($request->all());
            return $this->successResponse('Answer saved successfully', $data, 201);
        } catch (\Throwable $e) {
            Log::error($e);
            return $this->errorResponse('Failed to save answer', 500);
        }
    }

    public function update(UpdateUserAnswerRequest $request, UserAnswer $userAnswer)
    {
        try {
            $data = $this->userAnswerService->update($userAnswer, $request->validated());
            return $this->successResponse('Answer updated successfully', $data);
        } catch (\Throwable $e) {
            Log::error($e);
            return $this->errorResponse('Failed to update answer', 500);
        }
    }

public function destroy($id)
{
    try {
        $this->userAnswerService->delete($id);
        return $this->successResponse('Answer deleted successfully');
    } catch (\Throwable $e) {
        Log::error($e);
        return $this->errorResponse('Failed to delete answer', 500);
    }
}

public function showUserAnswers($userAnswer)
{
    try {
        $answers = $this->userAnswerService->getAnswersByUser($userAnswer);
        return $this->successResponse('User answers retrieved successfully.', $answers);
    } catch (\Throwable $e) {
        Log::error($e);
        return $this->errorResponse('Could not retrieve user answers', 500);
    }
}
}