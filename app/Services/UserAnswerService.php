<?php

namespace App\Services;

use App\Models\ProfileQuestion;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;

class UserAnswerService{
    public function create(array $data)
{

        $data['user_id'] = Auth::id();

        $userAnswer = UserAnswer::create($data);

        return $userAnswer;
    }

    public function update(UserAnswer $userAnswer, array $data)
{
    $userAnswer->update($data);
    return $userAnswer;
}

    public function delete($id)
    {
        $userAnswer = UserAnswer::findOrFail($id);
        return $userAnswer->delete();
    }

    public function getAnswersByUser(int $userAnswer)
    {
        return UserAnswer::with(['question'])
            ->where('user_id', $userAnswer)
            ->get();
    }

    public function getById($id)
    {
        return UserAnswer::with(['user', 'question'])->findOrFail($id);
    }
}