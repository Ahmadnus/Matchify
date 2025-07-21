<?php

namespace App\Services;

use App\Models\ProfileQuestion;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\ModelNotFoundException;

    class UserAnswerService
    {
        public function create(array $data)
        {
            $userId = Auth::id();
            $questionId = $data['question_id'] ?? null;
            $answer = $data['answer'] ?? null;

            if (!$questionId || !$answer) {
                throw new \Exception("Question ID and answer are required.");
            }

            // تحقق من وجود السؤال
            $question = Question::find($questionId);
            if (!$question) {
                throw new ModelNotFoundException("Question not found.");
            }

            // تحقق من عدم وجود إجابة مسبقة لنفس السؤال من نفس المستخدم
            $existing = UserAnswer::where('user_id', $userId)
                ->where('question_id', $questionId)
                ->first();

            if ($existing) {
                throw new \Exception("You have already answered this question.");
            }

            // إنشاء الإجابة
            return UserAnswer::create([
                'user_id' => $userId,
                'question_id' => $questionId,
                'answer' => $answer,
            ]);
        }

        public function update(UserAnswer $userAnswer, array $data)
        {
            $userId = Auth::id();

            // تأكد أن المستخدم يملك هذه الإجابة
            if ($userAnswer->user_id !== $userId) {
                throw new \Exception("You are not authorized to update this answer.");
            }

            if (isset($data['question_id']) || isset($data['user_id'])) {
                throw new \Exception("You cannot change question or user.");
            }

            $userAnswer->update([
                'answer' => $data['answer'] ?? $userAnswer->answer,
            ]);

            return $userAnswer;
        }

        public function delete($id)
        {
            $userAnswer = UserAnswer::findOrFail($id);
            $userId = Auth::id();

            // تأكد أن المستخدم يملك هذه الإجابة
            if ($userAnswer->user_id !== $userId) {
                throw new \Exception("You are not authorized to delete this answer.");
            }

            return $userAnswer->delete();
        }

        public function getAnswersByUser(int $userId)
        {
            return UserAnswer::with('question')
                ->where('user_id', $userId)
                ->get();
        }

        public function getById($id)
        {
            return UserAnswer::with(['user', 'question'])->findOrFail($id);
        }
    }