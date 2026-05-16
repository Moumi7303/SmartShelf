<?php

namespace Database\Factories;

use App\Models\Ebook;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EbookFactory extends Factory
{
    protected $model = Ebook::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'file_path' => 'ebooks/' . $this->faker->uuid() . '.pdf',
            'file_size' => $this->faker->numberBetween(1024000, 50000000), // 1MB to 50MB
            'mime_type' => 'application/pdf',
            'access_level' => $this->faker->randomElement(['public', 'students', 'faculty', 'restricted']),
            'uploaded_by' => User::factory(),
        ];
    }
}
