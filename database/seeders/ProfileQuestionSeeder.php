<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('questions')->insert([
            [
                'question' => 'What is your gender?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'What is your car type?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Do you want to enable notifications?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
