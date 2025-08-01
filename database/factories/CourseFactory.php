<?php

namespace Database\Factories;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
 public function definition(): array
{
    return [
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'category' => $this->faker->randomElement(['IT', 'Accounting', 'Science']),
        'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
        'objectives' => json_encode([
            'Understand core concepts',
            'Build practical skills',
            'Complete real-world projects'
        ]),
        'course_content' => json_encode([
            'Module 1: Introduction',
            'Module 2: Intermediate Topics',
            'Module 3: Final Project'
        ]),
        'instructor' => $this->faker->name,
        'user_id' => \App\Models\User::inRandomOrder()->first()->id ?? \App\Models\User::factory()
    ];
}
}
