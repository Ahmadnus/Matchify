<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnblockUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'blocked_id' => 'required|exists:users,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}