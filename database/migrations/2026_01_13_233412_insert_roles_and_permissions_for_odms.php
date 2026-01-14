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
        if (! Schema::hasTable('permissions')) {
            return;
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = 'web';
        $types = ['incoming', 'outgoing', 'memo', 'personal'];
        $actions = ['view', 'create', 'update', 'delete'];

        foreach ($types as $type) {
            foreach ($actions as $action) {
                $name = "letters.$type.$action";
                Permission::findOrCreate($name, $guard);
            }
        }
        Role::create(
            [
                'name' => 'superadmin',
                'guard_name' => $guard,
            ]
        );
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        if (! Schema::hasTable('permissions')) {
            return;
        }

        $types = ['incoming', 'outgoing', 'memo', 'personal'];
        $actions = ['view', 'create', 'update', 'delete'];
        $names = [];
        foreach ($types as $t) {
            foreach ($actions as $a) {
                $names[] = "letters.$t.$a";
            }
        }

        Permission::query()->whereIn('name', $names)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
