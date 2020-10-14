<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Genre::insert([
            ['name' => 'Fantasy', 'created_at' => now()],
            ['name' => 'Mystery', 'created_at' => now()],
            ['name' => 'Novel', 'created_at' => now()],
            ['name' => 'Science fiction', 'created_at' => now()],
            ['name' => 'Thriller', 'created_at' => now()],
            ['name' => 'Horror', 'created_at' => now()],
        ]);
    }
}
