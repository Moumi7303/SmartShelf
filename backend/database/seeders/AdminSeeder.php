<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Create the default super admin user.
     */
    public function run(): void
    {
        $superAdminRole = DB::table('roles')->where('name', 'super_admin')->first();
        $mainBranch = DB::table('branches')->first();

        DB::table('users')->insert([
            'name'              => 'Super Admin',
            'email'             => 'admin@smartshelf.edu',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'phone'             => '+880-1700-000000',
            'role_id'           => $superAdminRole?->id ?? 1,
            'branch_id'         => $mainBranch?->id ?? 1,
            'status'            => 'active',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Also create a branch admin for testing
        $branchAdminRole = DB::table('roles')->where('name', 'branch_admin')->first();
        DB::table('users')->insert([
            'name'              => 'Branch Admin',
            'email'             => 'branchadmin@smartshelf.edu',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'phone'             => '+880-1700-000001',
            'role_id'           => $branchAdminRole?->id ?? 2,
            'branch_id'         => $mainBranch?->id ?? 1,
            'status'            => 'active',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Create a librarian user
        $librarianRole = DB::table('roles')->where('name', 'librarian')->first();
        DB::table('users')->insert([
            'name'              => 'Head Librarian',
            'email'             => 'librarian@smartshelf.edu',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'phone'             => '+880-1700-000002',
            'role_id'           => $librarianRole?->id ?? 3,
            'branch_id'         => $mainBranch?->id ?? 1,
            'status'            => 'active',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Create a student member
        $studentRole = DB::table('roles')->where('name', 'student_member')->first();
        DB::table('users')->insert([
            'name'              => 'John Student',
            'email'             => 'student@smartshelf.edu',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'phone'             => '+880-1700-000003',
            'role_id'           => $studentRole?->id ?? 4,
            'branch_id'         => $mainBranch?->id ?? 1,
            'status'            => 'active',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }
}
