<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PeopleService
{
    /**
     * Create a new class instance.
     */
    public function getNearbyUsers(array $filters)
    {
        $userId = Auth::id();
        $authUser = User::findOrFail($userId);

        $lat      = $authUser->latitude;
        $long     = $authUser->longitude;
        $distance = $filters['distance'] ?? 10;
        $gender   = $filters['gender'] ?? null;
        $minAge   = $filters['min_age'] ?? null;
        $maxAge   = $filters['max_age'] ?? null;

        $query = User::select('*')
            ->selectRaw("
                6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                ) AS distance
            ", [$lat, $long, $lat])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('id', '!=', $userId)
            ->having('distance', '<', $distance);

        if ($gender) {
            $query->where('gender', $gender);
        }

        if (!is_null($minAge)) {
            $maxBirthDate = Carbon::now()->subYears($minAge)->endOfDay();
            $query->whereDate('date_of_birth', '<=', $maxBirthDate);
        }

        if (!is_null($maxAge)) {
            $minBirthDate = Carbon::now()->subYears($maxAge)->startOfDay();
            $query->whereDate('date_of_birth', '>=', $minBirthDate);
        }

        return $query->get();
    }
}
