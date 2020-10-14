<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'first_name' => 'Admin name',
                'last_name' => 'Admin surname',
                'email' => 'admin@email.com',
                'password' => Hash::make('12345678'),
                'created_at' => now()
            ],
            [
                'first_name' => 'Marko',
                'last_name' => 'Marković',
                'email' => 'marko@email.com',
                'password' => Hash::make('12345678'),
                'created_at' => now()
            ],
            [
                'first_name' => 'Ivan',
                'last_name' => 'Ivanović',
                'email' => 'ivan@email.com',
                'password' => Hash::make('12345678'),
                'created_at' => now()
            ],
        ]);
    }
}
