<?php

namespace App\Services;


use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\ValidationException;
class AuthService
{
    public function register(array $data)
    {

$data = User::create([
    'name' => $data['name'],
    'email' => $data['email'],
    'gender' => $data['gender'],

    'password' => Hash::make($data['password']),
]);

        return $data;
    }



    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('The provided credentials are incorrect.')],
            ]);
        }

        // حذف التوكنات القديمة إن أردت
        $user->tokens()->delete();

        // إنشاء توكن جديد
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'user'  => $user,
        ];
    }
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

}
