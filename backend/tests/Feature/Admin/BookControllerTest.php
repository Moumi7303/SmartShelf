<?php

use App\Models\Book;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->role = Role::factory()->create(['name' => 'super_admin']);
    $this->superAdmin = User::factory()->create([
        'role_id' => $this->role->id,
        'status' => 'active'
    ]);
});

test('super admin can view books index', function () {
    Book::factory()->count(3)->create();

    $response = $this->actingAs($this->superAdmin)->get(route('admin.books.index'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.books.index');
    $response->assertViewHas('books');
});

test('super admin can view book create form', function () {
    $response = $this->actingAs($this->superAdmin)->get(route('admin.books.create'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.books.create');
});

test('super admin can store new book', function () {
    $category = Category::factory()->create();
    $publisher = Publisher::factory()->create();
    $author = App\Models\Author::factory()->create();

    $bookData = [
        'isbn' => '978-3-16-148410-0',
        'title' => 'Test Book Title',
        'category_id' => $category->id,
        'publisher_id' => $publisher->id,
        'author_id' => $author->id,
        'publication_year' => 2024,
        'language' => 'English',
        'description' => 'A test book description.',
        'status' => 'available'
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('admin.books.store'), $bookData);

    $response->assertRedirect(route('admin.books.index'));
    $this->assertDatabaseHas('books', ['isbn' => '978-3-16-148410-0', 'title' => 'Test Book Title']);
});

test('super admin can view book edit form', function () {
    $book = Book::factory()->create();

    $response = $this->actingAs($this->superAdmin)->get(route('admin.books.edit', $book));

    $response->assertStatus(200);
    $response->assertViewIs('admin.books.edit');
    $response->assertViewHas('book');
});

test('super admin can update book', function () {
    $book = Book::factory()->create();
    $author = App\Models\Author::factory()->create();

    $response = $this->actingAs($this->superAdmin)->put(route('admin.books.update', $book), [
        'isbn' => $book->isbn,
        'title' => 'Updated Book Title',
        'category_id' => $book->category_id,
        'publisher_id' => $book->publisher_id,
        'author_id' => $author->id,
        'publication_year' => 2025,
        'language' => 'Spanish',
        'status' => 'available'
    ]);

    $response->assertRedirect(route('admin.books.index'));
    $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Updated Book Title', 'language' => 'Spanish']);
});

test('super admin can delete book', function () {
    $book = Book::factory()->create();

    $response = $this->actingAs($this->superAdmin)->delete(route('admin.books.destroy', $book));

    $response->assertRedirect(route('admin.books.index'));
    $this->assertSoftDeleted('books', ['id' => $book->id]);
});
