<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/** Փորձնական գրքեր՝ Book::factory()->count(30)->create() — PHP Դաս 20 */
class BookFactory extends Factory
{
    public function definition(): array
    {
        $title = rtrim(fake()->unique()->sentence(3), '.');
        $pages = fake()->numberBetween(40, 600);

        return [
            'title'      => $title,
            'author'     => fake()->name(),
            'year'       => fake()->optional(0.8)->numberBetween(1850, 2025),
            'pages'      => $pages,
            'read_pages' => fake()->randomElement([0, intdiv($pages, 3), $pages]),
            'rating'     => fake()->numberBetween(0, 5),
            'color'      => fake()->hexColor(),
            'format'     => 'paper',
        ];
    }

    /** slug-ը fillable չէ, ուստի դնում ենք ստեղծելուց առաջ */
    public function configure(): static
    {
        return $this->afterMaking(fn (Book $book) => $book->slug = Book::uniqueSlugFor($book->title));
    }
}
