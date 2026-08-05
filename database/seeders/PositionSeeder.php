<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\Department;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            // Direksi - Grade 1
            ['name' => 'General Manager',       'code' => 'DIR', 'grade' => 1],
            // HR
            ['name' => 'Kepala Departemen HR',  'code' => 'HR',  'grade' => 1],
            ['name' => 'Supervisor HR',         'code' => 'HR',  'grade' => 1],
            ['name' => 'Staff HR',              'code' => 'HR',  'grade' => 2],
            ['name' => 'Admin HR',              'code' => 'HR',  'grade' => 2],
            // Finance
            ['name' => 'Kepala Departemen FA',  'code' => 'FA',  'grade' => 1],
            ['name' => 'Supervisor Finance',    'code' => 'FA',  'grade' => 1],
            ['name' => 'Staff Finance',         'code' => 'FA',  'grade' => 2],
            ['name' => 'Admin Finance',         'code' => 'FA',  'grade' => 2],
            // Operations
            ['name' => 'Kepala Departemen OPS', 'code' => 'OPS', 'grade' => 1],
            ['name' => 'Supervisor Operasional','code' => 'OPS', 'grade' => 1],
            ['name' => 'Staff Operasional',     'code' => 'OPS', 'grade' => 2],
            // IT
            ['name' => 'Kepala Departemen IT',  'code' => 'IT',  'grade' => 1],
            ['name' => 'Supervisor IT',         'code' => 'IT',  'grade' => 1],
            ['name' => 'Staff IT',              'code' => 'IT',  'grade' => 2],
            // Marketing
            ['name' => 'Kepala Departemen MKT', 'code' => 'MKT', 'grade' => 1],
            ['name' => 'Staff Marketing',       'code' => 'MKT', 'grade' => 2],
            // GA
            ['name' => 'Kepala Departemen GA',  'code' => 'GA',  'grade' => 1],
            ['name' => 'Staff GA',              'code' => 'GA',  'grade' => 2],
        ];

        foreach ($positions as $pos) {
            $dept = Department::where('code', $pos['code'])->first();
            if ($dept) {
                Position::firstOrCreate(
                    ['name' => $pos['name'], 'department_id' => $dept->id],
                    ['grade_level' => $pos['grade'], 'department_id' => $dept->id]
                );
            }
        }
    }
}