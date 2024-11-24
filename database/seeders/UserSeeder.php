<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Demo',
            'email' => 'admin@mail.com',
            'password' => '$2y$10$zRyiQgBRXIJFBieYQ/QtEuk/eL/WK.WdbREVXJcJe4KksPpM2Twyu'
        ]);

        $relawan = User::create([
            'name' => 'Relawan Demo',
            'email' => 'relawan@mail.com',
            'password' => '$2y$10$zRyiQgBRXIJFBieYQ/QtEuk/eL/WK.WdbREVXJcJe4KksPpM2Twyu',
        ]);

        $admin->assignRole('admin');
        $relawan->assignRole('relawan');
    }
}
