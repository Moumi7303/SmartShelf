<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Member;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        $reservationDate = $this->faker->dateTimeBetween('-1 week', 'now');
        $expiryDate = (clone $reservationDate)->modify('+3 days');
        
        return [
            'member_id' => Member::factory(),
            'book_id' => Book::factory(),
            'reservation_date' => $reservationDate,
            'expiry_date' => $expiryDate,
            'queue_position' => $this->faker->numberBetween(1, 5),
            'status' => $this->faker->randomElement(['pending', 'approved', 'collected', 'expired', 'cancelled']),
        ];
    }
}
