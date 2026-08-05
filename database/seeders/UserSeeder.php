<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\Position;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole  = Role::where('slug', 'admin')->first();
        $superRole  = Role::where('slug', 'super-user')->first();
        $hrDept     = Department::where('code', 'HR')->first();
        $dirDept    = Department::where('code', 'DIR')->first();
        $gmPosition = Position::where('name', 'General Manager')->first();
        $hrAdmin    = Position::where('name', 'Admin HR')->first();

        // Admin (Mba Debby)
        User::firstOrCreate(
            ['employee_id' => 'MSK-001'],
            [
                'name'          => 'Debby',
                'email'         => 'debby@ptmsk.co.id',
                'password'      => Hash::make('Admin@1234'),
                'department_id' => $hrDept?->id,
                'position_id'   => $hrAdmin?->id,
                'role_id'       => $adminRole?->id,
                'grade'         => 2,
                'is_active'     => true,
                'birth_date'    => '1990-01-01',
            ]
        );

        // Super User
        User::firstOrCreate(
            ['employee_id' => 'MSK-002'],
            [
                'name'          => 'Super Admin',
                'email'         => 'superadmin@ptmsk.co.id',
                'password'      => Hash::make('Super@1234'),
                'department_id' => $dirDept?->id,
                'position_id'   => $gmPosition?->id,
                'role_id'       => $superRole?->id,
                'grade'         => 1,
                'is_active'     => true,
                'birth_date'    => '1985-06-15',
            ]
        );
    }
}