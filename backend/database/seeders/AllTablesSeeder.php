<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AuditLog;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\EmailLog;
use App\Models\Fine;
use App\Models\LoginLog;
use App\Models\Member;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Publisher;
use App\Models\Reservation;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;

class AllTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder populates EVERY single table in the database
     * to ensure no table is missed, as requested.
     */
    public function run(): void
    {
        // 1. Roles & Permissions
        $role = Role::factory()->create(['name' => 'Test Role ' . Str::random(5)]);
        $permission = Permission::factory()->create(['name' => 'test.permission.' . Str::random(5)]);
        
        // 2. role_permissions pivot table
        DB::table('role_permissions')->insertOrIgnore([
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);

        // 3. Branches
        $branch = Branch::factory()->create([
            'code' => Str::upper(Str::random(10)),
        ]);

        // 4. Users
        $user = User::factory()->create([
            'email' => 'testuser_' . Str::random(5) . '@example.com',
            'role_id' => $role->id,
            'branch_id' => $branch->id,
        ]);

        // 5. Taxonomy (Categories, Authors, Publishers)
        $category = Category::factory()->create();
        $author = Author::factory()->create();
        $publisher = Publisher::factory()->create();

        // 6. Books & Copies
        $book = Book::factory()->create([
            'category_id' => $category->id,
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
        ]);

        $bookCopy = BookCopy::factory()->create([
            'book_id' => $book->id,
            'branch_id' => $branch->id,
        ]);

        // 7. Ebooks
        Ebook::factory()->create([
            'book_id' => $book->id,
        ]);

        // 8. Members
        $member = Member::factory()->create([
            'user_id' => $user->id,
        ]);

        // 9. Circulation (Transactions & Reservations)
        $transaction = Transaction::factory()->create([
            'book_copy_id' => $bookCopy->id,
            'member_id' => $member->id,
            'issued_by' => $user->id,
        ]);

        $reservation = Reservation::factory()->create([
            'book_id' => $book->id,
            'member_id' => $member->id,
        ]);

        // 10. Fines & Payments
        $fine = Fine::factory()->create([
            'transaction_id' => $transaction->id,
            'member_id' => $member->id,
        ]);

        Payment::factory()->create([
            'fine_id' => $fine->id,
        ]);

        // 11. System Logs & Notifications
        Notification::factory()->create();
        EmailLog::factory()->create();
        AuditLog::factory()->create();
        LoginLog::factory()->create();

        // 12. Settings Table
        DB::table('settings')->insertOrIgnore([
            'key' => 'test_setting_' . Str::random(5),
            'value' => 'test_value',
            'type' => 'string',
            'group' => 'general',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 13. System/Framework Tables
        // Personal Access Tokens
        DB::table('personal_access_tokens')->insertOrIgnore([
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
            'name' => 'Test Token',
            'token' => hash('sha256', Str::random(40)),
            'abilities' => '["*"]',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Jobs Table
        DB::table('jobs')->insertOrIgnore([
            'queue' => 'default',
            'payload' => json_encode(['job' => 'TestJob']),
            'attempts' => 0,
            'reserved_at' => null,
            'available_at' => now()->timestamp,
            'created_at' => now()->timestamp,
        ]);

        // Cache Table
        DB::table('cache')->insertOrIgnore([
            'key' => 'test_cache_key_' . Str::random(5),
            'value' => 'test_value',
            'expiration' => now()->addDay()->timestamp,
        ]);
        
        $this->command->info('AllTablesSeeder successfully seeded data into ALL application and system tables.');
    }
}
