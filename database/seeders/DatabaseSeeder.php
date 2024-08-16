<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Edrian',
            'email' => 'edrian@gmail.com',
            'role' => 'Super User',
            'EmployeeID' => '101106',
            'password' => Hash::make('admin')
        ]);
    }
}
