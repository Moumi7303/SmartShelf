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
     * Matches RBAC spec: super_admin, branch_admin, librarian, student_member, guest_user
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
                'name' => 'branch_admin',
                'description' => 'Branch administrator — manages library operations within assigned branch.',
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
                'name' => 'student_member',
                'description' => 'Library member — can browse catalog, view loans, make reservations, and download eBooks.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'guest_user',
                'description' => 'Guest — limited read-only access to browse the public catalog.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
