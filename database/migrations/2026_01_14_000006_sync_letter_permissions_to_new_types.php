<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private array $newTypes = ['external', 'internal', 'memo', 'personal_request', 'outgoing'];
    private array $actions = ['view', 'create', 'update', 'delete'];
    private string $guard = 'web';

    public function up(): void
    {
        if (! Schema::hasTable('permissions')) {
            return;
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Desired permission names
        $desired = [];
        foreach ($this->newTypes as $t) {
            foreach ($this->actions as $a) {
                $desired[] = "letters.$t.$a";
            }
        }

        // Insert missing desired permissions
        foreach ($desired as $name) {
            Permission::findOrCreate($name, $this->guard);
        }

        // Remove any letters.* permissions not in desired list
        $toDelete = Permission::query()
            ->where('guard_name', $this->guard)
            ->where('name', 'like', 'letters.%')
            ->whereNotIn('name', $desired)
            ->pluck('id')
            ->all();

        if (! empty($toDelete)) {
            // Delete from permissions; cascades will handle pivot links
            Permission::query()->whereIn('id', $toDelete)->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        if (! Schema::hasTable('permissions')) {
            return;
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $oldTypes = ['incoming', 'outgoing', 'memo', 'personal'];
        $desired = [];
        foreach ($oldTypes as $t) {
            foreach ($this->actions as $a) {
                $desired[] = "letters.$t.$a";
            }
        }

        // Insert missing old permissions
        foreach ($desired as $name) {
            Permission::findOrCreate($name, $this->guard);
        }

        // Remove any new-type permissions
        $newNames = [];
        foreach ($this->newTypes as $t) {
            foreach ($this->actions as $a) {
                $newNames[] = "letters.$t.$a";
            }
        }
        if (! empty($newNames)) {
            Permission::query()->whereIn('name', $newNames)->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
