<?php

namespace App\Http\Controllers;

use App\Http\Requests\NearbyUsersRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\PeopleService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
class PeopleController extends Controller
{


    public function __construct(private PeopleService $peopleService)
    {
        //
    }
    use ApiResponse;

    public function index(NearbyUsersRequest $request, PeopleService $peopleService)
    {
        $filters = $request->validated();
        $users = $peopleService->getNearbyUsers($filters);

        return $this->successResponse('Nearby users retrieved successfully.', UserResource::collection($users)->response()->getData(true));
    }
}
