<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->email,
        'gender' => $this->gender,
        'date_of_birth' => $this->date_of_birth,
        'latitude' => $this->latitude,
        'longitude' => $this->longitude,
        'distance' => round($this->distance, 2) . ' km', // المسافة بالتقريب
    ];
}}
