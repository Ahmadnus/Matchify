<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NearbyUsersRequest extends FormRequest
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

        'distance'   => 'nullable|numeric|min:1',
        'gender'     => 'nullable|in:male,female',
        'min_age'    => 'nullable|integer|min:0',
        'max_age'    => 'nullable|integer|min:0|gte:min_age',
    ];
}
    public function messages(): array
    {
        return [
            'latitude.required' => 'Latitude is required.',
            'longitude.required' => 'Longitude is required.',
            'distance.numeric' => 'Distance must be a number.',
        ];
    }
}
