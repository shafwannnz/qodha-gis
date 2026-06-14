<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->truncate();
        DB::table('admins')->insert([
            'name'       => 'Admin Qodha',
            'email'      => 'admin@qodha.id',
            'password'   => Hash::make('qodha2024'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Admin seeded: admin@qodha.id / qodha2024');
    }
}
