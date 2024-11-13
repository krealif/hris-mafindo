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
        $adminDemo = User::create([
            'name' => 'Admin Demo',
            'email' => 'admin@mail.com',
            'password' => '$2y$10$VUKpKLFF9e1r9Gul6uiBKO6k0kEDi808NuW3dh77Ylh8JK15eWoY6'
        ]);

        $adminDemo->assignRole('admin');
    }
}
