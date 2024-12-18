<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions.
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions.
        foreach (PermissionEnum::cases() as $permission) {
            Permission::updateOrCreate(['name' => $permission->value]);
        }
        // Create roles.
        foreach (RoleEnum::cases() as $permission) {
            Role::updateOrCreate(['name' => $permission->value]);
        }

        // Create admin roles and assign permissions.
        $admin = Role::updateOrCreate(['name' => RoleEnum::ADMIN->value]);
    }
}
