<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::create([
            'name' => 'Anonymus',
            'pseudo' => 'unknown',
            'wikipedia_url' => 'https://en.wikipedia.org/wiki/Anonymous_work',
        ]);

        Author::factory(10)->create();
    }
}
