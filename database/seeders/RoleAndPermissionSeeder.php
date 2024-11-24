<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        foreach (PermissionsEnum::cases() as $permission) {
            Permission::updateOrCreate(['name' => $permission->value]);
        }
        // Create admin roles and assign permissions.
        $admin = Role::updateOrCreate(['name' => RolesEnum::ADMIN->value]);
        $admin->syncPermissions([
            PermissionsEnum::VIEW_ALL_LETTER,
            PermissionsEnum::REVIEW_LETTER,
        ]);

        $relawan = Role::updateOrCreate(['name' => RolesEnum::RELAWAN->value]);
        $relawan->syncPermissions([
            PermissionsEnum::VIEW_LETTER,
            PermissionsEnum::CREATE_LETTER,
            PermissionsEnum::EDIT_LETTER,
            PermissionsEnum::DELETE_LETTER,
        ]);
    }
}
