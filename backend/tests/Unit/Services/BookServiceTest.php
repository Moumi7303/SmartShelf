<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\BookService;
use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $bookService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->bookService = new BookService();
    }

    public function test_get_books_returns_paginated_data()
    {
        Book::factory()->count(3)->create();

        $result = $this->bookService->getBooks([]);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(3, $result->total());
    }

    public function test_create_book_saves_to_database()
    {
        $category = Category::factory()->create();
        $author = Author::factory()->create();
        $publisher = Publisher::factory()->create();

        $data = [
            'title' => 'Test Book',
            'isbn' => '1234567890',
            'category_id' => $category->id,
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'status' => 'available'
        ];
        
        $result = $this->bookService->createBook($data);

        $this->assertInstanceOf(Book::class, $result);
        $this->assertDatabaseHas('books', ['title' => 'Test Book']);
        $this->assertNotNull($result->barcode);
    }

    public function test_update_book_saves_changes()
    {
        $book = Book::factory()->create(['title' => 'Old Title']);
        $updateData = ['title' => 'New Title'];
        
        $result = $this->bookService->updateBook($book, $updateData);

        $this->assertInstanceOf(Book::class, $result);
        $this->assertEquals('New Title', $result->title);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'New Title']);
    }

    public function test_delete_book_soft_deletes_from_database()
    {
        $book = Book::factory()->create();
        
        $result = $this->bookService->deleteBook($book);

        $this->assertTrue($result);
        $this->assertSoftDeleted('books', ['id' => $book->id]);
    }

    public function test_check_availability_returns_correct_data()
    {
        $book = Book::factory()->create();
        \App\Models\BookCopy::factory()->count(3)->create([
            'book_id' => $book->id,
            'availability_status' => 'available'
        ]);
        \App\Models\BookCopy::factory()->count(2)->create([
            'book_id' => $book->id,
            'availability_status' => 'issued'
        ]);

        $result = $this->bookService->checkAvailability($book);

        $this->assertEquals([
            'total_copies' => 5,
            'available_copies' => 3,
            'is_available' => true
        ], $result);
    }
}
