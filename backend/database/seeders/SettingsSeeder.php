<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('settings')->insert([
            // Circulation settings
            ['key' => 'loan_period_days',       'value' => '14',   'group' => 'circulation', 'type' => 'integer', 'description' => 'Default loan period in days',           'created_at' => $now, 'updated_at' => $now],
            ['key' => 'max_loans_per_member',   'value' => '5',    'group' => 'circulation', 'type' => 'integer', 'description' => 'Maximum simultaneous active loans',      'created_at' => $now, 'updated_at' => $now],
            ['key' => 'max_renewals',           'value' => '2',    'group' => 'circulation', 'type' => 'integer', 'description' => 'Maximum renewals per transaction',        'created_at' => $now, 'updated_at' => $now],
            ['key' => 'renewal_period_days',    'value' => '7',    'group' => 'circulation', 'type' => 'integer', 'description' => 'Days added per renewal',                 'created_at' => $now, 'updated_at' => $now],

            // Fine settings
            ['key' => 'daily_fine_rate',        'value' => '1.00', 'group' => 'fines',       'type' => 'string',  'description' => 'Daily fine rate for overdue books ($)',   'created_at' => $now, 'updated_at' => $now],
            ['key' => 'max_fine_amount',        'value' => '50.00','group' => 'fines',       'type' => 'string',  'description' => 'Maximum fine cap per transaction ($)',     'created_at' => $now, 'updated_at' => $now],
            ['key' => 'block_on_unpaid_fine',   'value' => 'true', 'group' => 'fines',       'type' => 'boolean', 'description' => 'Block new loans if member has unpaid fines', 'created_at' => $now, 'updated_at' => $now],

            // Reservation settings
            ['key' => 'reservation_expiry_days','value' => '3',    'group' => 'reservations','type' => 'integer', 'description' => 'Days before approved reservation expires','created_at' => $now, 'updated_at' => $now],
            ['key' => 'max_reservations',       'value' => '3',    'group' => 'reservations','type' => 'integer', 'description' => 'Maximum active reservations per member',  'created_at' => $now, 'updated_at' => $now],

            // Membership settings
            ['key' => 'membership_duration_months', 'value' => '12', 'group' => 'membership', 'type' => 'integer', 'description' => 'Default membership duration in months', 'created_at' => $now, 'updated_at' => $now],

            // Notification settings
            ['key' => 'reminder_days_before_due','value' => '2',   'group' => 'notifications','type' => 'integer','description' => 'Send reminder N days before due date',   'created_at' => $now, 'updated_at' => $now],
            ['key' => 'send_overdue_emails',     'value' => 'true','group' => 'notifications','type' => 'boolean','description' => 'Send email on overdue',                  'created_at' => $now, 'updated_at' => $now],

            // General settings
            ['key' => 'library_name',           'value' => 'SmartShelf University Library', 'group' => 'general', 'type' => 'string', 'description' => 'Library system name',  'created_at' => $now, 'updated_at' => $now],
            ['key' => 'currency_symbol',        'value' => '$',    'group' => 'general',    'type' => 'string',  'description' => 'Currency symbol for fines and payments',   'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
