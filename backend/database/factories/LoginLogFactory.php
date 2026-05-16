<?php

namespace Database\Factories;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class LoginLogFactory extends Factory
{
    protected $model = LoginLog::class;

    public function definition(): array
    {
        $loginTime = $this->faker->dateTimeBetween('-1 week', 'now');
        $logoutTime = $this->faker->optional(0.8)->dateTimeInInterval($loginTime, '+2 hours');
        
        return [
            'user_id' => User::factory(),
            'login_time' => $loginTime,
            'logout_time' => $logoutTime,
            'ip_address' => $this->faker->ipv4(),
            'device_info' => $this->faker->userAgent(),
        ];
    }
}
