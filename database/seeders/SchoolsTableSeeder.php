<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolsTableSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            ['name' => 'Valenzuela Central Elementary', 'level_type' => 'elementary'],
            ['name' => 'Valenzuela Elementary Annex', 'level_type' => 'elementary'],
            ['name' => 'Valenzuela West High School', 'level_type' => 'junior_high'],
            ['name' => 'Valenzuela Senior High', 'level_type' => 'senior_high'],
            ['name' => 'Valenzuela Kindergarten Center', 'level_type' => 'kindergarten'],
            // add more placeholders as needed
        ];

        foreach ($schools as $s) {
            School::create($s);
        }
    }
}
