<?php

namespace Database\Factories;

use App\Models\BookCopy;
use App\Models\Book;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookCopyFactory extends Factory
{
    protected $model = BookCopy::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'branch_id' => Branch::factory(),
            'accession_number' => 'ACC-' . $this->faker->unique()->numberBetween(10000, 99999),
            'barcode' => 'BC-' . strtoupper(Str::random(8)),
            'availability_status' => $this->faker->randomElement(['available', 'issued', 'reserved', 'lost', 'damaged']),
            'condition_status' => $this->faker->randomElement(['good', 'damaged', 'repair_required']),
            'acquired_date' => $this->faker->date(),
        ];
    }
}
