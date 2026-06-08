<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Order matters: roles → permissions → branches → admin → categories → settings → sample data
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
            SettingsSeeder::class,
            AllTablesSeeder::class,
        ]);
    }
}
