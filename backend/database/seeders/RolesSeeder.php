<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the roles table with default SmartShelf system roles.
     */
    public function run(): void
    {
        $now = now();

        DB::table('roles')->insert([
            [
                'name' => 'super_admin',
                'description' => 'Full system access — manages all branches, users, and configurations.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'admin',
                'description' => 'Administrative access — manages library operations within assigned branch.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'librarian',
                'description' => 'Library staff — handles book circulation, catalog management, and member services.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'assistant',
                'description' => 'Library assistant — limited access for basic circulation tasks.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'member',
                'description' => 'Library member — can browse catalog, view loans, and make reservations.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
