<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
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
            'nama' => 'Admin Mafindo',
            'email' => 'admin@mail.com',
            'is_verified' => true,
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('admin');

        $relawan = User::create([
            'nama' => 'Relawan Mafindo',
            'email' => 'relawan@mail.com',
            'is_verified' => true,
            'password' => Hash::make('password'),
        ]);

        $relawan->assignRole('relawan');

        UserDetail::create([
            'user_id' => $relawan->id,
            'panggilan' => 'Relawan',
            'tgl_lahir' => '2000-01-01',
        ]);
    }
}
