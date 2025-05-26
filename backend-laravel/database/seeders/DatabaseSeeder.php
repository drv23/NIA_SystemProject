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

        Condition::factory()->create([
            'condition' => 'Serviceable',
        ]);

        Location::factory()->create([
            'location' => 'Panabo',
        ]);

        ConditionNumber::factory()->create([
            'condition_number' => 'A1',
        ]);

        Category::factory()->create([
            'category' => 'Desktop',
        ]);

    }
}
