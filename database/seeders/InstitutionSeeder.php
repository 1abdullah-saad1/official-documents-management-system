<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->getData();

        foreach ($data as $item) {
            $institution = \App\Models\Institution::updateOrCreate(
                $item['identifier'],
                $item['data']
            );

            // Auto-create institution-scoped roles (admin, user) for guard 'web'
            app(PermissionRegistrar::class)->setPermissionsTeamId($institution->id);
            $teamKey = config('permission.column_names.team_foreign_key');
            Role::query()->firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
                $teamKey => $institution->id,
            ]);
            Role::query()->firstOrCreate([
                'name' => 'user',
                'guard_name' => 'web',
                $teamKey => $institution->id,
            ]);
        }
    }

    public function getData(): array
    {
        return [
            ['identifier' => ['id' => '1'], 'data' => ['name' => 'مكتب رئيس الجامعة']],
            ['identifier' => ['id' => '2'], 'data' => ['name' => 'مكتب مساعد رئيس الجامعة للشؤون العلمية']],
            ['identifier' => ['id' => '3'], 'data' => ['name' => 'مكتب مساعد رئيس الجامعة للشؤون الادارية']],
            ['identifier' => ['id' => '4'], 'data' => ['name' => 'مركز الحاسبة الالكترونية']],
            ['identifier' => ['id' => '5'], 'data' => ['name' => 'مركز الحاسبة الالكترونية \ شعبة الانظمة والبرامجيات']],
        ];
    }
}
