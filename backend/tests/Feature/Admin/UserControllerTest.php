<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->role = Role::factory()->create(['name' => 'super_admin']);
    $this->superAdmin = User::factory()->create([
        'role_id' => $this->role->id,
        'status' => 'active'
    ]);
});

test('super admin can view users index', function () {
    User::factory()->count(3)->create();

    $response = $this->actingAs($this->superAdmin)->get(route('admin.users.index'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.users.index');
    $response->assertViewHas('users');
});

test('super admin can view user create form', function () {
    $response = $this->actingAs($this->superAdmin)->get(route('admin.users.create'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.users.create');
});

test('super admin can store new user', function () {
    $role = Role::factory()->create(['name' => 'librarian']);
    $branch = Branch::factory()->create();

    $userData = [
        'name' => 'New Librarian',
        'email' => 'librarian@smartshelf.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role_id' => $role->id,
        'branch_id' => $branch->id,
        'status' => 'active',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('admin.users.store'), $userData);

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', ['name' => 'New Librarian', 'email' => 'librarian@smartshelf.com']);
});

test('validation fails when storing user with missing data', function () {
    $response = $this->actingAs($this->superAdmin)->post(route('admin.users.store'), []);

    $response->assertSessionHasErrors(['name', 'email', 'password', 'role_id', 'status']);
});

test('super admin can view user edit form', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->superAdmin)->get(route('admin.users.edit', $user));

    $response->assertStatus(200);
    $response->assertViewIs('admin.users.edit');
    $response->assertViewHas('user');
});

test('super admin can update user', function () {
    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => 'branch_admin']);

    $response = $this->actingAs($this->superAdmin)->put(route('admin.users.update', $user), [
        'name' => 'Updated User',
        'email' => 'updated@smartshelf.com',
        'role_id' => $role->id,
        'branch_id' => $user->branch_id,
        'status' => 'inactive',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated User', 'status' => 'inactive']);
});

test('super admin can delete user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->superAdmin)->delete(route('admin.users.destroy', $user));

    $response->assertRedirect(route('admin.users.index'));
    $this->assertSoftDeleted('users', ['id' => $user->id]);
});
