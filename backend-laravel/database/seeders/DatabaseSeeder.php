<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Condition;
use App\Models\ConditionNumber;
use App\Models\Location;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'password' => Hash::make('password'),
        //     'role'     => 'admin',
        // ]);

        $conditions = ['Serviceable', 'Non - Serviceable', 'On Maintenance'];

        foreach ($conditions as $con) {
            Condition::factory()->create([
                'condition' => $con,
            ]);
        }

        $locations = ['Billing Unit', 'Engineering  ', 'O & M', 'COA', 'ICT', 'Admin & Finance', 'IDDD', 'Equipment'];

        foreach ($locations as $loc) {
            Location::factory()->create([
                'location' => $loc,
        ]);
        }

         $condition_numbers = ['A1', 'A2', 'A3', 'A4', 'A5'];

        foreach ($condition_numbers as $conn) {
            ConditionNumber::factory()->create([
                'condition_number' => $conn,
            ]);
        }

        $categories = ['Desktop', 'Consumables', 'ICT'];


        foreach ($categories as $cat) {
            Category::factory()->create([
                'category' => $cat,
            ]);
        }
    }

}