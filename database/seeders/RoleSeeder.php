<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin',      'slug' => 'admin',      'description' => 'Upload & manage dokumen'],
            ['name' => 'Super User', 'slug' => 'super-user', 'description' => 'Lihat semua dokumen'],
            ['name' => 'User',       'slug' => 'user',       'description' => 'Hak akses per grade'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}