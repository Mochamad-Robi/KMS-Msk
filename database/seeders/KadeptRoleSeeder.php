<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KadeptRoleSeeder extends Seeder
{
    public function run(): void
    {
        $exists = DB::table('roles')->where('slug', 'kadept')->exists();

        if (!$exists) {
            DB::table('roles')->insert([
                'name'        => 'Kadept',
                'slug'        => 'kadept',
                'description' => 'Menilai KPI karyawan yang di-assign',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $this->command->info('Role Kadept berhasil ditambahkan.');
        } else {
            $this->command->info('Role Kadept sudah ada, tidak ditambahkan ulang.');
        }
    }
}