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
        echo ("-- DELETING EXISTING DATA --\n");
        echo ("DELETING USER DATA...");
        DB::delete('delete from users');
        echo ("[OK]\n");

        echo ("DELETING CATEGORY DATA...");
        DB::delete('delete from categories');
        echo ("[OK]\n");

        echo ("-- USERS --\n");
        echo ("Adding user 'Admin User'...");
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'A',
        ]);
        echo ("[OK]\n");

        echo ("ADDING USER 'Lecturer User'...");
        DB::table('users')->insert([
            'name' => 'Lecturer User',
            'email' => 'lecturer@example.com',
            'password' => Hash::make('password'),
            'role' => 'L',
        ]);
        echo ("[OK]\n");

        echo ("ADDING USER 'Trainee User'...");
        DB::table('users')->insert([
            'name' => 'Trainee User',
            'email' => 'trainee@example.com',
            'password' => Hash::make('password'),
            'role' => 'T',
        ]);
        echo ("[OK]\n");

        echo ("-- CATEGORIES --\n");
        echo ("ADDING CATEGORY 'Web Vulnerabilities'...");
        DB::table('categories')->insert([
            'name' => 'Web Vulnerabilities',
            'training_type' => 'R'
        ]);
        echo ("[OK]\n");

        echo ("ADDING CATEGORY 'Monitoring'...");
        DB::table('categories')->insert([
            'name' => 'Monitoring',
            'training_type' => 'B'
        ]);
        echo ("[OK]\n");

        echo ("\nSEEDING COMPLETE\n\n");
    }
}
