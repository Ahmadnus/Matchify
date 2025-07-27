<?php

namespace App\Http\Requests;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreLikeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'status' => 'in:accepted,rejected', // فقط عند الرد
        ];
    }


    public function withValidator($validator)
{
    $validator->after(function (Validator $validator) {
        $senderId = Auth::id();
        $receiverId = $this->input('receiver_id');

        // تحقق من وجود طلب إعجاب معلق مسبقًا
        $likeExists = \App\Models\Like::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->where('status', 'pending')
            ->exists();

        if ($likeExists) {
            $validator->errors()->add('receiver_id', 'You have already sent a pending like request to this user.');
        }

        // تحقق من أن المستقبل موجود في جدول الأصدقاء (مثلاً شرط إضافي حسب منطقك)
        $alreadyFriend = \App\Models\Friend::where(function ($query) use ($senderId, $receiverId) {
                $query->where('user_id', $senderId)->where('friend_id', $receiverId);
            })
            ->orWhere(function ($query) use ($senderId, $receiverId) {
                $query->where('user_id', $receiverId)->where('friend_id', $senderId);
            })
            ->exists();

        if ($alreadyFriend) {
            $validator->errors()->add('receiver_id', 'You are already friends with this user.');
        }
    });
}
}
