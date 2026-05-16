<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'author_id' => Author::factory(),
            'publisher_id' => Publisher::factory(),
            'title' => ucwords($this->faker->words(rand(2, 5), true)),
            'isbn' => $this->faker->unique()->isbn13(),
            'edition' => $this->faker->numberBetween(1, 5) . 'th Edition',
            'language' => $this->faker->randomElement(['English', 'Spanish', 'French', 'German']),
            'publication_year' => $this->faker->year(),
            'description' => $this->faker->paragraph(),
            'cover_image' => null,
            'barcode' => Book::generateBarcode(),
            'shelf_location' => strtoupper($this->faker->lexify('?-')) . $this->faker->numberBetween(100, 999),
            'status' => $this->faker->randomElement(['available', 'unavailable', 'maintenance']),
        ];
    }
}
