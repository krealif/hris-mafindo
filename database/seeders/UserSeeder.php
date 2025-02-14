<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'nama' => 'IT Team',
            'email' => 'it@mafindo.or.id',
            'is_approved' => true,
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('admin');
    }
}
