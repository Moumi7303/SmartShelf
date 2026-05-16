<?php

namespace Database\Factories;

use App\Models\Fine;
use App\Models\Transaction;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class FineFactory extends Factory
{
    protected $model = Fine::class;

    public function definition(): array
    {
        $overdueDays = $this->faker->numberBetween(1, 30);
        $dailyRate = 1.00;
        $totalAmount = $overdueDays * $dailyRate;
        
        return [
            'transaction_id' => Transaction::factory(),
            'member_id' => Member::factory(),
            'overdue_days' => $overdueDays,
            'daily_rate' => $dailyRate,
            'total_amount' => $totalAmount,
            'status' => $this->faker->randomElement(['paid', 'unpaid', 'partial']),
        ];
    }
}
