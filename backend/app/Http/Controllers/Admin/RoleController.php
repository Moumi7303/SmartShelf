<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('name')->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy('module');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name'        => $validated['name'],
            'description' => $validated['description'],
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $systemRoles = ['super_admin', 'branch_admin', 'librarian', 'student_member', 'guest_user'];
        if (in_array($role->name, $systemRoles) && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'You cannot edit system roles.');
        }

        $permissions = Permission::all()->groupBy('module');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $systemRoles = ['super_admin', 'branch_admin', 'librarian', 'student_member', 'guest_user'];
        
        $rules = [
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ];

        // Only allow name change if not a system role
        if (!in_array($role->name, $systemRoles)) {
            $rules['name'] = 'required|string|max:255|unique:roles,name,' . $role->id;
        }

        $validated = $request->validate($rules);

        if (isset($validated['name'])) {
            $role->name = $validated['name'];
        }
        $role->description = $validated['description'];
        $role->save();

        // Super admin must keep all permissions
        if ($role->name === 'super_admin') {
            $role->permissions()->sync(Permission::pluck('id'));
        } else {
            $role->permissions()->sync($validated['permissions'] ?? []);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $systemRoles = ['super_admin', 'branch_admin', 'librarian', 'student_member', 'guest_user'];
        
        if (in_array($role->name, $systemRoles)) {
            return back()->with('error', 'Cannot delete system roles.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete a role that is assigned to users.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
