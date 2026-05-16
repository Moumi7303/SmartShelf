<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed default university library branches.
     */
    public function run(): void
    {
        $now = now();

        DB::table('branches')->insert([
            [
                'name' => 'Central Library',
                'code' => 'MAIN',
                'address' => 'Main Campus, Building A, Ground Floor',
                'phone' => '+880-2-000-0001',
                'email' => 'central@smartshelf.edu',
                'manager_id' => null,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Science & Engineering Library',
                'code' => 'SCI',
                'address' => 'Science Campus, Block C, 2nd Floor',
                'phone' => '+880-2-000-0002',
                'email' => 'science@smartshelf.edu',
                'manager_id' => null,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Arts & Humanities Library',
                'code' => 'ARTS',
                'address' => 'Arts Campus, Heritage Hall, 1st Floor',
                'phone' => '+880-2-000-0003',
                'email' => 'arts@smartshelf.edu',
                'manager_id' => null,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Medical Library',
                'code' => 'MED',
                'address' => 'Medical Campus, Hospital Complex, 3rd Floor',
                'phone' => '+880-2-000-0004',
                'email' => 'medical@smartshelf.edu',
                'manager_id' => null,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Digital Resource Center',
                'code' => 'DRC',
                'address' => 'IT Building, Room 201',
                'phone' => '+880-2-000-0005',
                'email' => 'digital@smartshelf.edu',
                'manager_id' => null,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
