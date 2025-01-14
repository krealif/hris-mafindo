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
            'is_approved' => true,
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('admin');

        $pengurus = User::create([
            'nama' => 'Pengurus Mafindo',
            'email' => 'Pengurus@mail.com',
            'is_approved' => true,
            'password' => Hash::make('password'),
            'branch_id' => 1,
        ]);

        $pengurus->assignRole('pengurus-wilayah');

        $relawan = User::create([
            'nama' => 'Relawan Mafindo',
            'email' => 'relawan@mail.com',
            'is_approved' => true,
            'password' => Hash::make('password'),
            'branch_id' => 1,
        ]);

        UserDetail::create([
            'user_id' => $relawan->id,
            'panggilan' => 'Relawan',
            'tgl_lahir' => '2000-01-01',
        ]);

        $relawan->assignRole('relawan-wilayah');
    }
}
