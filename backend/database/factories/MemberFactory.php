<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'membership_id' => 'MEM-' . $this->faker->unique()->numberBetween(100000, 999999),
            'student_id' => $this->faker->unique()->numberBetween(10000000, 99999999),
            'department' => $this->faker->randomElement(['Computer Science', 'Business', 'Arts', 'Engineering', 'Science']),
            'semester' => $this->faker->randomElement(['Fall 2025', 'Spring 2026', 'Summer 2026']),
            'address' => $this->faker->address(),
            'membership_status' => $this->faker->randomElement(['active', 'expired', 'suspended', 'pending']),
            'joined_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'expires_at' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}
