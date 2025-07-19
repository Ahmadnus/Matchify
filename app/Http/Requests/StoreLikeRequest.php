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

        $exists = \App\Models\Like::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            $validator->errors()->add('receiver_id', 'You have already sent a pending friend request to this user.');
        }
    });
}
}
