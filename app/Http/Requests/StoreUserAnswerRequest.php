<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserAnswerRequest extends FormRequest
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
    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => Auth::id(),
        ]);
    }
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string|min:1'
        ];
    }
}
