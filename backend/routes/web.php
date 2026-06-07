<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BookCopyController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EbookController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public facing routes (will be handled by Breeze auth or public catalog)
Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated Routes
Route::middleware(['auth', 'active_user'])->group(function () {
    
    // Dashboards
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications (All authenticated users)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read.all');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // Admin & Staff Routes (Protected by permission middleware)
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // System Settings
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index')->middleware('permission:settings.view');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update')->middleware('permission:settings.edit');

        // Audit Logs
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index')->middleware('permission:audit_logs.view');
        Route::get('audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show')->middleware('permission:audit_logs.view');

        // Roles & Permissions
        Route::resource('roles', RoleController::class)->middleware('permission:roles.view');
        Route::resource('permissions', PermissionController::class)->middleware('permission:permissions.view');

        // Users & Branches
        Route::resource('branches', BranchController::class)->middleware('permission:branches.view');
        Route::resource('users', UserController::class)->middleware('permission:users.view');
        
        // Members
        Route::resource('members', MemberController::class)->middleware('permission:members.view');
        Route::post('members/{member}/renew', [MemberController::class, 'renewMembership'])->name('members.renew')->middleware('permission:members.edit');

        // Catalog Management
        Route::resource('categories', CategoryController::class)->middleware('permission:categories.view');
        Route::resource('authors', AuthorController::class)->middleware('permission:authors.view');
        Route::resource('publishers', PublisherController::class)->middleware('permission:publishers.view');
        Route::resource('books', BookController::class)->middleware('permission:books.view');
        Route::resource('book-copies', BookCopyController::class)->middleware('permission:book_copies.view');
        
        // Digital Library
        Route::resource('ebooks', EbookController::class)->except(['show', 'edit', 'update'])->middleware('permission:ebooks.view');
        Route::get('ebooks/{ebook}/download', [EbookController::class, 'download'])->name('ebooks.download')->middleware('permission:ebooks.download');

        // Circulation / Transactions
        Route::get('transactions/available-copies', [TransactionController::class, 'getAvailableCopies'])->name('transactions.available-copies')->middleware('permission:transactions.create');
        Route::post('transactions/{transaction}/return', [TransactionController::class, 'returnBook'])->name('transactions.return')->middleware('permission:transactions.return');
        Route::post('transactions/{transaction}/renew', [TransactionController::class, 'renew'])->name('transactions.renew')->middleware('permission:transactions.renew');
        Route::resource('transactions', TransactionController::class)->except(['edit', 'update', 'destroy'])->middleware('permission:transactions.view');

        // Reservations
        Route::post('reservations/{reservation}/approve', [ReservationController::class, 'approve'])->name('reservations.approve')->middleware('permission:reservations.approve');
        Route::post('reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel')->middleware('permission:reservations.cancel');
        Route::resource('reservations', ReservationController::class)->except(['edit', 'update', 'destroy'])->middleware('permission:reservations.view');

        // Fines & Payments
        Route::post('fines/{fine}/payment', [FineController::class, 'recordPayment'])->name('fines.payment')->middleware('permission:payments.create');
        Route::post('fines/{fine}/waive', [FineController::class, 'waive'])->name('fines.waive')->middleware('permission:fines.waive');
        Route::resource('fines', FineController::class)->only(['index', 'show'])->middleware('permission:fines.view');

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:reports.view');
        Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory')->middleware('permission:reports.view');
        Route::get('reports/circulation', [ReportController::class, 'circulation'])->name('reports.circulation')->middleware('permission:reports.view');
        Route::get('reports/fines', [ReportController::class, 'fines'])->name('reports.fines')->middleware('permission:reports.view');
    });
});

require __DIR__.'/auth.php';
