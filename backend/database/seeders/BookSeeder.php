<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Create 100 books
        $books = Book::factory()->count(100)->create();

        // For each book, create 1 to 5 copies across different branches
        foreach ($books as $book) {
            BookCopy::factory()->count(rand(1, 5))->create([
                'book_id' => $book->id,
            ]);
        }
    }
}
