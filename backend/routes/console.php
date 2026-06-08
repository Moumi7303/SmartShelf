<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Transaction;
use App\Models\LoginLog;
use App\Models\AuditLog;
use App\Models\Fine;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $thirtyDaysAgo = Carbon::now()->subDays(30);
    $loginLogsDeleted = LoginLog::where('created_at', '<', $thirtyDaysAgo)->delete();
    $auditLogsDeleted = AuditLog::where('created_at', '<', $thirtyDaysAgo)->delete();
    Log::info("Purged $loginLogsDeleted login logs and $auditLogsDeleted audit logs.");
})->daily()->name('purge_old_logs');

Schedule::call(function () {
    $overdueTransactions = Transaction::where('status', 'issued')
        ->where('due_date', '<', Carbon::today())
        ->get();

    foreach ($overdueTransactions as $transaction) {
        $amount = $transaction->calculateFine();
        if ($amount > 0) {
            $fine = Fine::firstOrNew(['transaction_id' => $transaction->id]);
            $fine->member_id = $transaction->member_id;
            $fine->overdue_days = Carbon::today()->diffInDays($transaction->due_date);
            $fine->daily_rate = 1.00;
            $fine->total_amount = $amount;
            if (!$fine->exists) {
                $fine->status = 'unpaid';
            }
            $fine->save();
            
            if ($transaction->status !== 'overdue') {
                $transaction->update(['status' => 'overdue']);
            }
        }
    }
    Log::info("Processed fines for " . $overdueTransactions->count() . " overdue transactions.");
})->daily()->name('process_fines');

Schedule::call(function () {
    $dueTomorrow = Transaction::where('status', 'issued')
        ->whereDate('due_date', Carbon::tomorrow())
        ->with('member.user')
        ->get();

    foreach ($dueTomorrow as $transaction) {
        if ($transaction->member && $transaction->member->user) {
            // Placeholder for actual mail sending
            Log::info("Reminder: Book due tomorrow for user " . $transaction->member->user->email);
        }
    }
})->daily()->name('send_due_reminders');
