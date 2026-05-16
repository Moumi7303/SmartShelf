<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Fine;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'fine_id' => Fine::factory(),
            'payment_method' => $this->faker->randomElement(['cash', 'card', 'online', 'bank_transfer']),
            'amount' => $this->faker->randomFloat(2, 1, 50),
            'paid_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'received_by' => User::factory(),
            'transaction_reference' => strtoupper($this->faker->lexify('TXN-??????')),
        ];
    }
}
