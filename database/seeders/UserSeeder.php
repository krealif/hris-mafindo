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
            'nama' => 'Admin Mafindo',
            'email' => 'admin@mail.com',
            'is_verified' => true,
            'password' => '$2y$10$zRyiQgBRXIJFBieYQ/QtEuk/eL/WK.WdbREVXJcJe4KksPpM2Twyu'
        ]);

        $admin->assignRole('admin');
    }
}
