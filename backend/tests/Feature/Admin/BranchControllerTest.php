<?php

use App\Models\Branch;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->role = Role::factory()->create(['name' => 'super_admin']);
    $this->superAdmin = User::factory()->create([
        'role_id' => $this->role->id,
        'status' => 'active'
    ]);
});

test('super admin can view branches index', function () {
    Branch::factory()->count(3)->create();

    $response = $this->actingAs($this->superAdmin)->get(route('admin.branches.index'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.branches.index');
    $response->assertViewHas('branches');
});

test('super admin can view branch create form', function () {
    $response = $this->actingAs($this->superAdmin)->get(route('admin.branches.create'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.branches.create');
});

test('super admin can store new branch', function () {
    $branchData = [
        'name' => 'Central Library',
        'code' => 'CENT-01',
        'address' => '123 Main St',
        'phone' => '555-1234',
        'email' => 'central@smartshelf.com',
        'status' => 'active',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('admin.branches.store'), $branchData);

    $response->assertRedirect(route('admin.branches.index'));
    $this->assertDatabaseHas('branches', ['name' => 'Central Library', 'code' => 'CENT-01']);
});

test('validation fails when storing branch with missing data', function () {
    $response = $this->actingAs($this->superAdmin)->post(route('admin.branches.store'), []);

    $response->assertSessionHasErrors(['name', 'code']);
});

test('super admin can view branch edit form', function () {
    $branch = Branch::factory()->create();

    $response = $this->actingAs($this->superAdmin)->get(route('admin.branches.edit', $branch));

    $response->assertStatus(200);
    $response->assertViewIs('admin.branches.edit');
    $response->assertViewHas('branch');
});

test('super admin can update branch', function () {
    $branch = Branch::factory()->create();

    $response = $this->actingAs($this->superAdmin)->put(route('admin.branches.update', $branch), [
        'name' => 'Updated Library',
        'code' => $branch->code,
        'address' => '456 New St',
        'phone' => '555-9876',
        'email' => 'updated@smartshelf.com',
        'status' => 'active',
    ]);

    $response->assertRedirect(route('admin.branches.index'));
    $this->assertDatabaseHas('branches', ['id' => $branch->id, 'name' => 'Updated Library']);
});

test('super admin can delete branch', function () {
    $branch = Branch::factory()->create();

    $response = $this->actingAs($this->superAdmin)->delete(route('admin.branches.destroy', $branch));

    $response->assertRedirect(route('admin.branches.index'));
    $this->assertDatabaseMissing('branches', ['id' => $branch->id]);
});
