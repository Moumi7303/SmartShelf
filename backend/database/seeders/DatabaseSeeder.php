<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Order matters — seeders are run sequentially to satisfy
     * foreign key constraints:
     *   1. Roles (required by users)
     *   2. Permissions + role_permissions pivot
     *   3. Branches (required by users)
     *   4. Admin user (requires roles + branches)
     *   5. Categories, Authors, Publishers (standalone reference data)
     *   6. Books (requires categories, authors, publishers)
     *   7. Members (requires users)
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            PermissionsSeeder::class,
            BranchSeeder::class,
            AdminSeeder::class,
            CategorySeeder::class,
            AuthorSeeder::class,
            PublisherSeeder::class,
            BookSeeder::class,
            MemberSeeder::class,
        ]);
    }
}
