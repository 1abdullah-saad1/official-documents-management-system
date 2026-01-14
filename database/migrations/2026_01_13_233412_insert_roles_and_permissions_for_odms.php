<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('roles')
            || ! Schema::hasTable('permissions')
            || ! Schema::hasTable('role_has_permissions')
        ) {
            return;
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = 'web';

        $allPermissions = Permission::query()
            ->where('guard_name', $guard)
            ->where('name', 'like', 'letters.%')
            ->pluck('name')
            ->all();

        $superadmin = Role::query()->where('guard_name', $guard)->where('name', 'superadmin')->first();
        $admin = Role::query()->where('guard_name', $guard)->where('name', 'admin')->first();

        if ($superadmin) {
            $superadmin->syncPermissions($allPermissions);
        }

        if ($admin) {
            // admin gets all letter permissions, but institution-scope is enforced by team_id + route team context
            $admin->syncPermissions($allPermissions);
        }

        // NOTE: do NOT auto-assign permissions to "user" role here.
        // Users will be specialized by letter type (incoming/outgoing/memo/personal) per institution.

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('role_has_permissions')) {
            return;
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = 'web';

        $superadmin = Role::query()->where('guard_name', $guard)->where('name', 'superadmin')->first();
        $admin = Role::query()->where('guard_name', $guard)->where('name', 'admin')->first();

        if ($superadmin) {
            $superadmin->syncPermissions([]);
        }
        if ($admin) {
            $admin->syncPermissions([]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
