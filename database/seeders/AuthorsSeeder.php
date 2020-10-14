<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Author::insert([
            ['name' => 'Dan Brown', 'created_at' => now()],
            ['name' => 'Miguel de Cervantes', 'created_at' => now()],
            ['name' => 'J.K. Rowling', 'created_at' => now()],
            ['name' => 'Frank Herbert', 'created_at' => now()],
            ['name' => 'Rhiannon Frater', 'created_at' => now()],
            ['name' => 'Alex Michaelides', 'created_at' => now()],
            ['name' => 'Stieg Larsson', 'created_at' => now()],
            ['name' => 'J.R.R. Tolkien', 'created_at' => now()],
            ['name' => 'Harper Lee', 'created_at' => now()],
            ['name' => 'J.D. Salinger', 'created_at' => now()],
            ['name' => 'Ursula K. Le Guin', 'created_at' => now()],
            ['name' => 'Bram Stoker', 'created_at' => now()],
        ]);
    }
}
