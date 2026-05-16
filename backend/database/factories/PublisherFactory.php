<?php

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublisherFactory extends Factory
{
    protected $model = Publisher::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' Publishing',
            'address' => $this->faker->address(),
            'contact_info' => $this->faker->phoneNumber() . ' | ' . $this->faker->email(),
        ];
    }
}
