<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash as FacadesHash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'FirstName' => 'Edrian',
            'LastName' => 'Lagrosa',
            'UserName' => 'edrian123',
            'email' => 'edrian@gmail.com',
            'role' => 'Admin',
            'PIN' => '101106',
            'password' => FacadesHash::make('admin')
        ]);
    }
}
