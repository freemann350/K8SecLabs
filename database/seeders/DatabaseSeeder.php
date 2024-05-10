<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo ("--ADDING USERS--\n");
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'A',
        ]);
        echo ("User 'Admin User' added\n");

        DB::table('users')->insert([
            'name' => 'Lecturer User',
            'email' => 'lecturer@example.com',
            'password' => Hash::make('password'),
            'role' => 'L',
        ]);
        echo ("User 'Professor User' added\n");

        DB::table('users')->insert([
            'name' => 'Trainee User',
            'email' => 'trainee@example.com',
            'password' => Hash::make('password'),
            'role' => 'T',
        ]);
        echo ("User 'Trainee User' added\n\n");

        echo ("--ADDING CATEGORIES--\n");
        DB::table('categories')->insert([
            'name' => 'Web Vulnerabilities',
            'training_type' => 'R'
        ]);
        echo ("Category 'Web Vulnerabilities' added\n");

        DB::table('categories')->insert([
            'name' => 'Monitoring',
            'training_type' => 'B'
        ]);
        echo ("Category 'Monitoring' added\n");
    }
}
