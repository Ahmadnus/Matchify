<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $genders = ['male', 'female'];

    for ($i = 0; $i < 20; $i++) {
        User::create([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'), // كل المستخدمين نفس الباسوورد
            'gender' => $genders[array_rand($genders)],
            'latitude' => fake()->latitude(30.0, 40.0),   // ضمن مجال منطقي
            'longitude' => fake()->longitude(35.0, 45.0), // ضمن مجال منطقي
            'date_of_birth' => fake()->dateTimeBetween('-35 years', '-18 years')->format('Y-m-d'),
        ]);
    }
}
}
