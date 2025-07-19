<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use \Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{    use ApiResponse;
    public function __construct(private AuthService $authService)
    {

    }




    public function signup(SignUpRequest $request)
    {
        try {


            $data = $this->authService->register($request->validated());

            return $this->successResponse(
                trans('User created successfully'),

                201
            );

        } catch (Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                500
            );
        }
    }


    public function login(Request $request)
    {
        try {
            $data = $this->authService->login($request->only('email', 'password'));

            return $this->successResponse(
                __('Login successful'),
                $data,
                200
            );
        } catch (ValidationException $e) {
            return $this->errorResponse(
                __('Validation errors'),
                422
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                401
            );
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request->user());

            return $this->successResponse(
                __('Logout successful'),
                null,
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                401
            );
        }
    }


}
