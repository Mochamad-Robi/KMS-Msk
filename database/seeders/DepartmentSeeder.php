<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Direksi',                  'code' => 'DIR'],
            ['name' => 'Human Resource',            'code' => 'HR'],
            ['name' => 'Finance & Accounting',      'code' => 'FA'],
            ['name' => 'Operations',                'code' => 'OPS'],
            ['name' => 'Marketing',                 'code' => 'MKT'],
            ['name' => 'Information Technology',    'code' => 'IT'],
            ['name' => 'General Affairs',           'code' => 'GA'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['code' => $dept['code']], $dept);
        }
    }
}