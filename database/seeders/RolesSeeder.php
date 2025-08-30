<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Role::where('name', 'admin')->where('guard_name', 'web')->exists()) {
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
    }

    if (!Role::where('name', 'utilisateur_normal')->where('guard_name', 'web')->exists()) {
        Role::create(['name' => 'utilisateur_normal', 'guard_name' => 'web']);
    }
    }
}
