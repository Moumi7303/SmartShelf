<?php

use App\Models\User;
use App\Models\Role;
use App\Models\LoginLog;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->role = Role::factory()->create(['name' => 'student_member']);
    $this->user = User::factory()->create([
        'email' => 'test@smartshelf.com',
        'password' => Hash::make('password123'),
        'role_id' => $this->role->id,
        'status' => 'active',
    ]);
});

test('users can authenticate using the login screen', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'test@smartshelf.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200);
    $response->assertJsonPath('user.email', 'test@smartshelf.com');
    
    // Assert LoginLog was created
    expect(LoginLog::where('user_id', $this->user->id)->exists())->toBeTrue();
});

test('users can not authenticate with invalid password', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'test@smartshelf.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401);
});

test('inactive users are blocked from logging in', function () {
    $this->user->update(['status' => 'suspended']);

    $response = $this->postJson('/api/login', [
        'email' => 'test@smartshelf.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(403);
    $response->assertJsonFragment(['message' => 'Your account is suspended. Please contact administration.']);
});

test('authenticated users can logout', function () {
    Sanctum::actingAs($this->user);
    
    // Simulate a previous login
    $log = LoginLog::factory()->create(['user_id' => $this->user->id, 'logout_time' => null]);

    $response = $this->postJson('/api/logout');

    $response->assertStatus(200);
    
    // Assert logout_time was updated
    expect($log->fresh()->logout_time)->not->toBeNull();
});

test('guest users can register', function () {
    Role::factory()->create(['name' => 'guest_user']);
    \App\Models\Branch::factory()->create();

    $response = $this->postJson('/api/register', [
        'name' => 'Test Guest',
        'email' => 'guest@smartshelf.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('users', ['email' => 'guest@smartshelf.com']);
});
