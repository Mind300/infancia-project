<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Permissions Seeder Test
            PermissionsTableSeeder::class,
            // // Admin Seeder Test
            // UsersTableSeeder::class,
            // // Nursery Seeder Test
            // NurseriesTableSeeder::class,
            // // FAQS Seeder Test
            // FAQsTableSeeder::class,
            // // Policies Seeder Test
            // PolicyTableSeeder::class,
        ]);
    }
}
