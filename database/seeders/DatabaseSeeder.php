<?php

namespace Database\Seeders;

use App\Models\BookRental;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(AuthorsSeeder::class);
        $this->call(GenresSeeder::class);
        $this->call(BooksSeeder::class);
    }
}
