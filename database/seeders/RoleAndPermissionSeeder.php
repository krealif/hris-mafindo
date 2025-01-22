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

        // Define roles and their corresponding permissions
        $rolesPermissions = [
            RoleEnum::ADMIN->value => [
                PermissionEnum::VIEW_ALL_USER,
                PermissionEnum::EDIT_ALL_USER,
                PermissionEnum::VIEW_ALL_LETTER,
                PermissionEnum::CREATE_LETTER,
                PermissionEnum::CREATE_LETTER_FOR_RELAWAN,
                PermissionEnum::CREATE_LETTER_FOR_PENGURUS,
                PermissionEnum::HANDLE_LETTER,
                PermissionEnum::DELETE_ALL_LETTER,
                PermissionEnum::VIEW_EVENT,
                PermissionEnum::CREATE_EVENT,
                PermissionEnum::EDIT_EVENT,
                PermissionEnum::DELETE_EVENT,
                PermissionEnum::VIEW_PARTICIPANT,
                PermissionEnum::VIEW_ALL_CERTIFICATE,
                PermissionEnum::MANAGE_CERTIFICATE,
                PermissionEnum::VIEW_MATERIAL,
                PermissionEnum::CREATE_MATERIAL,
                PermissionEnum::EDIT_MATERIAL,
                PermissionEnum::DELETE_MATERIAL,
            ],
            RoleEnum::PENGURUS_WILAYAH->value => [
                PermissionEnum::VIEW_RELAWAN_USER,
                PermissionEnum::VIEW_LETTER,
                PermissionEnum::CREATE_LETTER,
                PermissionEnum::EDIT_LETTER,
                PermissionEnum::DELETE_LETTER,
                PermissionEnum::VIEW_RELAWAN_LETTER,
                PermissionEnum::CREATE_LETTER_FOR_RELAWAN,
                PermissionEnum::VIEW_MATERIAL,
            ],
            RoleEnum::RELAWAN_WILAYAH->value => [
                PermissionEnum::VIEW_LETTER,
                PermissionEnum::CREATE_LETTER,
                PermissionEnum::EDIT_LETTER,
                PermissionEnum::DELETE_LETTER,
                PermissionEnum::VIEW_EVENT,
                PermissionEnum::JOIN_EVENT,
                PermissionEnum::VIEW_CERTIFICATE,
                PermissionEnum::VIEW_MATERIAL,
            ],
            RoleEnum::RELAWAN_BARU->value => [
                PermissionEnum::VIEW_LETTER,
                PermissionEnum::CREATE_LETTER,
                PermissionEnum::EDIT_LETTER,
                PermissionEnum::DELETE_LETTER,
                PermissionEnum::VIEW_EVENT,
                PermissionEnum::JOIN_EVENT,
                PermissionEnum::VIEW_CERTIFICATE,
                PermissionEnum::VIEW_MATERIAL,
            ],
        ];

        // Create roles and sync permissions
        foreach ($rolesPermissions as $roleName => $permissions) {
            $role = Role::updateOrCreate(['name' => $roleName]);
            $role->syncPermissions($permissions);
        }
    }
}
