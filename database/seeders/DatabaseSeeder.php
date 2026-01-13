<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // If you want to seed default users, you can uncomment this:
        // \App\Models\User::factory(10)->create();

        // Call seeders here
        $this->call([
            SchoolsTableSeeder::class,
        ]);
    }
}
