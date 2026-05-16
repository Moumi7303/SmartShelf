<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the permissions table and assign them to roles.
     */
    public function run(): void
    {
        $now = now();

        $modules = [
            'users' => ['view', 'create', 'edit', 'delete'],
            'roles' => ['view', 'create', 'edit', 'delete'],
            'branches' => ['view', 'create', 'edit', 'delete'],
            'books' => ['view', 'create', 'edit', 'delete'],
            'book_copies' => ['view', 'create', 'edit', 'delete'],
            'ebooks' => ['view', 'create', 'edit', 'delete', 'download'],
            'categories' => ['view', 'create', 'edit', 'delete'],
            'authors' => ['view', 'create', 'edit', 'delete'],
            'publishers' => ['view', 'create', 'edit', 'delete'],
            'members' => ['view', 'create', 'edit', 'delete'],
            'transactions' => ['view', 'create', 'edit', 'return', 'renew'],
            'reservations' => ['view', 'create', 'edit', 'approve', 'cancel'],
            'fines' => ['view', 'create', 'edit', 'waive'],
            'payments' => ['view', 'create', 'refund'],
            'reports' => ['view', 'export'],
            'notifications' => ['view', 'create', 'delete'],
            'audit_logs' => ['view'],
            'settings' => ['view', 'edit'],
        ];

        $permissions = [];
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'name' => "{$module}.{$action}",
                    'module' => $module,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('permissions')->insert($permissions);

        // Assign all permissions to super_admin
        $allPermissionIds = DB::table('permissions')->pluck('id');
        $superAdminId = DB::table('roles')->where('name', 'super_admin')->value('id');

        if ($superAdminId) {
            $pivotData = $allPermissionIds->map(fn ($pid) => [
                'role_id' => $superAdminId,
                'permission_id' => $pid,
                'created_at' => $now,
                'updated_at' => $now,
            ])->toArray();

            DB::table('role_permissions')->insert($pivotData);
        }

        // Assign operational permissions to admin (exclude settings.edit, audit_logs)
        $adminId = DB::table('roles')->where('name', 'admin')->value('id');
        if ($adminId) {
            $adminPermissions = DB::table('permissions')
                ->whereNotIn('module', ['settings', 'audit_logs', 'roles'])
                ->pluck('id');

            $pivotData = $adminPermissions->map(fn ($pid) => [
                'role_id' => $adminId,
                'permission_id' => $pid,
                'created_at' => $now,
                'updated_at' => $now,
            ])->toArray();

            DB::table('role_permissions')->insert($pivotData);
        }

        // Assign librarian permissions
        $librarianId = DB::table('roles')->where('name', 'librarian')->value('id');
        if ($librarianId) {
            $librarianPermissions = DB::table('permissions')
                ->whereIn('module', [
                    'books', 'book_copies', 'ebooks', 'categories', 'authors', 'publishers',
                    'members', 'transactions', 'reservations', 'fines', 'payments',
                    'notifications', 'reports',
                ])
                ->pluck('id');

            $pivotData = $librarianPermissions->map(fn ($pid) => [
                'role_id' => $librarianId,
                'permission_id' => $pid,
                'created_at' => $now,
                'updated_at' => $now,
            ])->toArray();

            DB::table('role_permissions')->insert($pivotData);
        }

        // Assign member permissions (view-only + reservations)
        $memberId = DB::table('roles')->where('name', 'member')->value('id');
        if ($memberId) {
            $memberPermissions = DB::table('permissions')
                ->where(function ($q) {
                    $q->whereIn('name', [
                        'books.view', 'categories.view', 'authors.view', 'publishers.view',
                        'ebooks.view', 'ebooks.download',
                        'transactions.view',
                        'reservations.view', 'reservations.create', 'reservations.cancel',
                        'fines.view', 'notifications.view',
                    ]);
                })
                ->pluck('id');

            $pivotData = $memberPermissions->map(fn ($pid) => [
                'role_id' => $memberId,
                'permission_id' => $pid,
                'created_at' => $now,
                'updated_at' => $now,
            ])->toArray();

            DB::table('role_permissions')->insert($pivotData);
        }
    }
}
