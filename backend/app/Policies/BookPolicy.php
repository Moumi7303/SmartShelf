<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('books.view') || $user->hasRole('guest_user');
    }

    public function view(User $user, Book $book): bool
    {
        return $user->hasPermission('books.view') || $user->hasRole('guest_user');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('books.create');
    }

    public function update(User $user, Book $book): bool
    {
        return $user->hasPermission('books.edit');
    }

    public function delete(User $user, Book $book): bool
    {
        return $user->hasPermission('books.delete');
    }

    public function restore(User $user, Book $book): bool
    {
        return $user->hasPermission('books.restore') ?? $user->hasPermission('books.edit');
    }

    public function forceDelete(User $user, Book $book): bool
    {
        return $user->hasRole('super_admin');
    }
}
