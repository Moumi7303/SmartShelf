<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => $this->faker->randomElement(['created', 'updated', 'deleted']),
            'module' => $this->faker->randomElement(['books', 'members', 'transactions', 'settings']),
            'ip_address' => $this->faker->ipv4(),
            'old_values' => json_encode(['status' => 'inactive']),
            'new_values' => json_encode(['status' => 'active']),
        ];
    }
}
