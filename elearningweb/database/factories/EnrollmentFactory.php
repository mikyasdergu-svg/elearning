<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enrollment>
 */
class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'price' => fake()->randomFloat(2, 0.99, 199.99),
            'paid' => false,
            'payment_status' => 'pending',
            'payment_provider' => null,
            'payment_intent_id' => null,
            'trial_ends_at' => now()->addDays(8),
            'grace_ends_at' => now()->addDays(8)->addHours(12),
        ];
    }
}
