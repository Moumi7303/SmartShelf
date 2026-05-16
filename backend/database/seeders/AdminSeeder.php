<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Create the default SmartShelf super admin user.
     */
    public function run(): void
    {
        $superAdminRoleId = DB::table('roles')->where('name', 'super_admin')->value('id');
        $mainBranchId = DB::table('branches')->where('code', 'MAIN')->value('id');

        User::create([
            'role_id' => $superAdminRoleId,
            'branch_id' => $mainBranchId,
            'name' => 'SmartShelf Admin',
            'email' => 'admin@smartshelf.edu',
            'phone' => '+880-1700-000000',
            'password' => Hash::make('smartshelf2026'),
            'email_verified_at' => now(),
            'status' => 'active',
        ]);
    }
}
