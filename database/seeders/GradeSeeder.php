<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $grades = [
            ['name' => 'General Manager',      'code' => 'GM',     'level' => 1],
            ['name' => 'Kepala Departemen',     'code' => 'Kadept', 'level' => 2],
            ['name' => 'Supervisor',            'code' => 'SPV',    'level' => 3],
            ['name' => 'Admin',                 'code' => 'Admin',  'level' => 4],
            ['name' => 'Staff',                 'code' => 'Staff',  'level' => 5],
        ];

        foreach ($grades as $grade) {
            Grade::updateOrCreate(['level' => $grade['level']], $grade);
        }
    }
}