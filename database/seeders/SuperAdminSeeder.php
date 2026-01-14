<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $role = Role::query()->firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
        ]);

        $user = User::query()->firstOrCreate(
            ['email' => 'abdullahs.mahmood@uomosul.edu.iq'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('11223344'),
            ]
        );

        // Global superadmin role (team_id null). This works with teams enabled.
        $user->assignRole($role);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
