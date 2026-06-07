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
            'users'         => ['view', 'create', 'edit', 'delete'],
            'roles'         => ['view', 'create', 'edit', 'delete'],
            'permissions'   => ['view', 'create', 'edit', 'delete'],
            'branches'      => ['view', 'create', 'edit', 'delete'],
            'books'         => ['view', 'create', 'edit', 'delete', 'restore'],
            'book_copies'   => ['view', 'create', 'edit', 'delete'],
            'ebooks'        => ['view', 'create', 'edit', 'delete', 'download'],
            'categories'    => ['view', 'create', 'edit', 'delete'],
            'authors'       => ['view', 'create', 'edit', 'delete'],
            'publishers'    => ['view', 'create', 'edit', 'delete'],
            'members'       => ['view', 'create', 'edit', 'delete'],
            'transactions'  => ['view', 'create', 'edit', 'return', 'renew'],
            'reservations'  => ['view', 'create', 'edit', 'approve', 'cancel'],
            'fines'         => ['view', 'create', 'edit', 'waive'],
            'payments'      => ['view', 'create', 'refund'],
            'reports'       => ['view', 'export'],
            'notifications' => ['view', 'create', 'delete'],
            'audit_logs'    => ['view'],
            'settings'      => ['view', 'edit'],
        ];

        $permissions = [];
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'name'       => "{$module}.{$action}",
                    'module'     => $module,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('permissions')->insert($permissions);

        // ─── super_admin: ALL permissions ─────────────────────────────
        $allPermissionIds = DB::table('permissions')->pluck('id');
        $superAdminId = DB::table('roles')->where('name', 'super_admin')->value('id');

        if ($superAdminId) {
            $this->assignPermissions($superAdminId, $allPermissionIds->toArray(), $now);
        }

        // ─── branch_admin: Everything except system settings, roles, audit_logs ──
        $branchAdminId = DB::table('roles')->where('name', 'branch_admin')->value('id');
        if ($branchAdminId) {
            $perms = DB::table('permissions')
                ->whereNotIn('module', ['settings', 'audit_logs', 'roles', 'permissions'])
                ->pluck('id');
            // Also allow viewing audit logs and settings
            $extraPerms = DB::table('permissions')
                ->whereIn('name', ['audit_logs.view', 'settings.view', 'roles.view'])
                ->pluck('id');
            $this->assignPermissions($branchAdminId, $perms->merge($extraPerms)->unique()->toArray(), $now);
        }

        // ─── librarian: Operational permissions ──────────────────────
        $librarianId = DB::table('roles')->where('name', 'librarian')->value('id');
        if ($librarianId) {
            $perms = DB::table('permissions')
                ->whereIn('module', [
                    'books', 'book_copies', 'ebooks', 'categories', 'authors', 'publishers',
                    'members', 'transactions', 'reservations', 'fines', 'payments',
                    'notifications', 'reports',
                ])
                ->pluck('id');
            $this->assignPermissions($librarianId, $perms->toArray(), $now);
        }

        // ─── student_member: Read-only + reservations + own data ─────
        $studentId = DB::table('roles')->where('name', 'student_member')->value('id');
        if ($studentId) {
            $perms = DB::table('permissions')
                ->whereIn('name', [
                    'books.view', 'categories.view', 'authors.view', 'publishers.view',
                    'ebooks.view', 'ebooks.download',
                    'transactions.view',
                    'reservations.view', 'reservations.create', 'reservations.cancel',
                    'fines.view', 'payments.view',
                    'notifications.view',
                ])
                ->pluck('id');
            $this->assignPermissions($studentId, $perms->toArray(), $now);
        }

        // ─── guest_user: Browse catalog only ─────────────────────────
        $guestId = DB::table('roles')->where('name', 'guest_user')->value('id');
        if ($guestId) {
            $perms = DB::table('permissions')
                ->whereIn('name', [
                    'books.view', 'categories.view', 'authors.view', 'publishers.view',
                    'notifications.view',
                ])
                ->pluck('id');
            $this->assignPermissions($guestId, $perms->toArray(), $now);
        }
    }

    /**
     * Assign a set of permission IDs to a role.
     */
    private function assignPermissions(int $roleId, array $permissionIds, $now): void
    {
        $pivotData = array_map(fn ($pid) => [
            'role_id'       => $roleId,
            'permission_id' => $pid,
            'created_at'    => $now,
            'updated_at'    => $now,
        ], $permissionIds);

        DB::table('role_permissions')->insert($pivotData);
    }
}
