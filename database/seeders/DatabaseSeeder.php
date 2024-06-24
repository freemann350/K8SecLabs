<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Http\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo ("DELETING DEFINITIONS DATA...");
        if (Storage::exists('definitions')) {
            Storage::deleteDirectory('definitions');
        }
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
        $webVuln = DB::table('categories')->insert([
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
        
        echo ("-- DEFINITIONS --\n");
        echo ("ADDING DEFINITION 'kali-juice-shop'...");

        $description = file_get_contents(__DIR__ . '/definitions/kali-juice-shop_description.txt');
        $definition_file = new File(__DIR__ . "/definitions/kali-juice-shop.json");

        Storage::createDirectory('definitions');
        
        Storage::putFileAs('definitions', $definition_file,'kali-juice-shop.json');

        DB::table('definitions')->insert([
            'name' => 'kali-juice-shop',
            'user_id' => 1,
            'category_id' => $webVuln,
            'path' => "definitions/kali-juice-shop.json",
            'private' => 0,
            'description' => $description,
            'tags' => 'owasp-juice-shop,penetration-testing,kali-linux,vulnerability-assessment,web-application-security,ethical-hacking'
        ]);
        echo ("[OK]\n");
        
        echo ("ADDING DEFINITION 'owasp-juice-shop-simple'...");

        $description = file_get_contents(__DIR__ . '/definitions/owasp-juice-shop_description.txt');
        $definition_file = new File(__DIR__ . "/definitions/owasp-juice-shop.json");
        
        Storage::putFileAs('definitions', $definition_file,'owasp-juice-shop.json');

        DB::table('definitions')->insert([
            'name' => 'owasp-juice-shop',
            'user_id' => 1,
            'category_id' => $webVuln,
            'path' => "definitions/owasp-juice-shop.json",
            'private' => 0,
            'description' => $description,
            'tags' => 'owasp-juice-shop,vulnerability-assessment,web-application-security,ethical-hacking'
        ]);
        echo ("[OK]\n");

        echo ("ADDING DEFINITION 'flask-ctf'...");

        $description = file_get_contents(__DIR__ . '/definitions/flask-ctf_description.txt');
        $definition_file = new File(__DIR__ . "/definitions/flask-ctf.json");
        
        Storage::putFileAs('definitions', $definition_file,'flask-ctf.json');

        DB::table('definitions')->insert([
            'name' => 'flask-ctf',
            'user_id' => 2,
            'category_id' => $webVuln,
            'path' => "definitions/flask-ctf.json",
            'private' => 0,
            'description' => $description,
            'tags' => 'flask-app,ctf,web-vulnerabilities'
        ]);
        echo ("[OK]\n");
        echo ("\nSEEDING COMPLETE\n\n");
    }
}
