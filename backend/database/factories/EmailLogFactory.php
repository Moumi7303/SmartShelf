<?php

namespace Database\Factories;

use App\Models\EmailLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailLogFactory extends Factory
{
    protected $model = EmailLog::class;

    public function definition(): array
    {
        return [
            'recipient_email' => $this->faker->email(),
            'subject' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['sent', 'failed', 'pending', 'queued']),
            'sent_at' => $this->faker->optional()->dateTime(),
            'failure_reason' => null,
        ];
    }
}
