<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

/**
 * Խորացված — PHP Դաս 21, տնային 7
 * php artisan make:policy BookPolicy --model=Book
 * Երկու Gate-ը՝ մեկ դասում։ Laravel-ը ինքն է գտնում այն (Book → BookPolicy)
 * Կոնտրոլերում՝ Gate::authorize('delete-books') → $this->authorize('delete', $book) կամ Gate::authorize('delete', $book)
 * Շաբլոնում՝ @can('delete-books') → @can('delete', $book)
 * Եթե անցնում ես Policy-ի, AppServiceProvider-ի երկու Gate::define-ը ջնջիր, որ կանոնը լինի ՄԵԿ տեղում
 */
class BookPolicy
{
    public function create(User $user): bool
    {
        return $user->isStaff();
    }

    public function update(User $user, Book $book): bool
    {
        return $user->isStaff();
    }

    public function delete(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }

    public function restore(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }
}
