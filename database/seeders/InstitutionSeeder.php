<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->getData();

        foreach ($data as $item) {
            \App\Models\Institution::updateOrCreate(
                $item['identifier'],
                $item['data']
            );
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
