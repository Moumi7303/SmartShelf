<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\MemberController;

// Public Auth Routes
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);

// Password Reset (Guest)
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
Route::post('/reset-password', [PasswordResetController::class, 'reset']);

// Protected Routes
Route::middleware(['auth:sanctum', 'active_user'])->group(function () {
    
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::get('/user', fn (Request $request) => $request->user()->load('role.permissions'));

    // Email Verification
    Route::post('/email/resend', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link sent!']);
    });

    // Role & Permission Management (Super Admin & Admins with explicit permission)
    Route::middleware(['permission:roles.view'])->group(function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/{role}', [RoleController::class, 'show']);
    });
    
    Route::middleware(['permission:roles.create'])->post('/roles', [RoleController::class, 'store']);
    Route::middleware(['permission:roles.edit'])->put('/roles/{role}', [RoleController::class, 'update']);
    Route::middleware(['permission:roles.delete'])->delete('/roles/{role}', [RoleController::class, 'destroy']);

    Route::middleware(['permission:permissions.view'])->get('/permissions', [PermissionController::class, 'index']);
    Route::middleware(['permission:permissions.create'])->post('/permissions', [PermissionController::class, 'store']);
    Route::middleware(['permission:permissions.edit'])->put('/permissions/{permission}', [PermissionController::class, 'update']);
    Route::middleware(['permission:permissions.delete'])->delete('/permissions/{permission}', [PermissionController::class, 'destroy']);

    // Books & Catalog (Using branch_access middleware to restrict branch-specific cataloging if necessary)
    Route::middleware(['branch_access'])->group(function () {
        Route::apiResource('books', BookController::class);
        Route::apiResource('authors', AuthorController::class);
        Route::apiResource('categories', CategoryController::class);
        
        // Transactions & Loans
        Route::get('transactions', [TransactionController::class, 'index']);
        Route::post('transactions/checkout', [TransactionController::class, 'checkout']);
        Route::post('transactions/{transaction}/return', [TransactionController::class, 'returnBook']);
        
        // Ebooks
        Route::get('ebooks', [EbookController::class, 'index']);
        Route::get('ebooks/{ebook}/download', [EbookController::class, 'download']);
        Route::get('ebooks/{ebook}/stream', [EbookController::class, 'stream']);
        
        // Members
        Route::apiResource('members', MemberController::class)->only(['index', 'show']);
    });

});
