<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'teacher_id' => User::factory()->create([
                'role' => 'teacher',
                'teacher_status' => 'approved',
            ])->id,
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(4),
            'price' => fake()->randomFloat(2, 0.99, 199.99),
            'thumbnail' => null,
            'status' => fake()->randomElement(['draft', 'published']),
        ];
    }
}
