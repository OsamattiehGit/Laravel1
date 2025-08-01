<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
public function run(): void
{
    $users = \App\Models\User::factory()->count(10)->create();

    $courses = collect();
    for ($i = 0; $i < 10; $i++) {
        $randomUser = $users->random();

        $courses->push(\App\Models\Course::factory()->create([
            'user_id' => $randomUser->id,
        ]));
    }

    for ($i = 0; $i < 20; $i++) {
        \App\Models\Enrollment::factory()->create([
            'user_id' => $users->random()->id,
            'course_id' => $courses->random()->id,
        ]);
    }
}




}
