<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Member;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->role = Role::factory()->create(['name' => 'super_admin']);
    $this->superAdmin = User::factory()->create([
        'role_id' => $this->role->id,
        'status' => 'active'
    ]);
});

test('super admin can view members index', function () {
    Member::factory()->count(3)->create();

    $response = $this->actingAs($this->superAdmin)->get(route('admin.members.index'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.members.index');
    $response->assertViewHas('members');
});

test('super admin can view member create form', function () {
    $response = $this->actingAs($this->superAdmin)->get(route('admin.members.create'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.members.create');
});

test('super admin can store new member', function () {
    $role = Role::factory()->create(['name' => 'student_member']);
    $branch = Branch::factory()->create();

    $memberData = [
        'name' => 'New Student Member',
        'email' => 'student@smartshelf.com',
        'phone' => '1234567890',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'branch_id' => $branch->id,
        'student_id' => 'STU-12345',
        'department' => 'Computer Science',
        'semester' => '1st',
        'address' => '123 Test St',
    ];

    $response = $this->actingAs($this->superAdmin)->post(route('admin.members.store'), $memberData);

    $response->assertRedirect(route('admin.members.index'));
    $this->assertDatabaseHas('users', ['email' => 'student@smartshelf.com']);
    $this->assertDatabaseHas('members', ['student_id' => 'STU-12345']);
});

test('super admin can view member edit form', function () {
    $member = Member::factory()->create();

    $response = $this->actingAs($this->superAdmin)->get(route('admin.members.edit', $member));

    $response->assertStatus(200);
    $response->assertViewIs('admin.members.edit');
    $response->assertViewHas('member');
});

test('super admin can update member', function () {
    $member = Member::factory()->create();
    $role = Role::factory()->create(['name' => 'faculty_member']);

    $updateData = [
        'name' => 'Updated Faculty',
        'email' => 'faculty@smartshelf.com',
        'phone' => '9876543210',
        'branch_id' => $member->user->branch_id,
        'student_id' => 'FAC-001',
        'department' => 'Mathematics',
        'semester' => '2nd',
        'address' => '456 Faculty Rd',
        'membership_status' => 'active',
    ];

    $response = $this->actingAs($this->superAdmin)->put(route('admin.members.update', $member), $updateData);

    $response->assertRedirect(route('admin.members.index'));
    $this->assertDatabaseHas('users', ['id' => $member->user_id, 'name' => 'Updated Faculty']);
});

test('super admin can view member details', function () {
    $member = Member::factory()->create();

    $response = $this->actingAs($this->superAdmin)->get(route('admin.members.show', $member));

    $response->assertStatus(200);
    $response->assertViewIs('admin.members.show');
    $response->assertViewHas('member');
});
