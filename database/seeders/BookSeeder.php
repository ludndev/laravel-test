<?php

namespace Database\Seeders;

use App\Enums\RentStatusEnum;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::all()->each(fn(Author $author) => Book::factory(10)->create([
            'author_id' => $author->id,
        ])->each(fn(Book $book) => $book->genres()
            ->attach(Genre::all()
                ->random(rand(1, 5))
                ->pluck('id')
                ->toArray()
            )
        ));

        $userId = 1;
        Book::find(1)->users()->attach($userId, [
            'status' => RentStatusEnum::RENTED,
        ]);
    }
}
