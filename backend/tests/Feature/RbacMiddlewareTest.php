<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->permission = Permission::factory()->create(['name' => 'roles.view']);
    $this->role = Role::factory()->create(['name' => 'admin']);
    $this->role->permissions()->attach($this->permission);
    
    $this->user = User::factory()->create(['role_id' => $this->role->id]);
    
    $this->superAdminRole = Role::factory()->create(['name' => 'super_admin']);
    $this->superAdmin = User::factory()->create(['role_id' => $this->superAdminRole->id]);
    
    $this->guestRole = Role::factory()->create(['name' => 'guest_user']);
    $this->guestUser = User::factory()->create(['role_id' => $this->guestRole->id]);
});

test('user with permission can access protected route', function () {
    Sanctum::actingAs($this->user);

    $response = $this->getJson('/api/roles');

    $response->assertStatus(200);
});

test('super admin bypasses permission checks', function () {
    Sanctum::actingAs($this->superAdmin);

    // Super admin doesn't have the permission explicitly attached, but policy/middleware should allow
    $response = $this->getJson('/api/roles');

    $response->assertStatus(200);
});

test('user without permission is forbidden', function () {
    Sanctum::actingAs($this->guestUser);

    $response = $this->getJson('/api/roles');

    $response->assertStatus(403);
});

test('unauthenticated user is unauthorized', function () {
    $response = $this->getJson('/api/roles');

    $response->assertStatus(401);
});

test('suspended active_user middleware blocks request', function () {
    $this->superAdmin->update(['status' => 'suspended']);
    Sanctum::actingAs($this->superAdmin);

    $response = $this->getJson('/api/roles');

    $response->assertStatus(403);
});
