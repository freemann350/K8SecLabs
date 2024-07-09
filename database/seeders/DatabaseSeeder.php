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
        echo ("DROPPING DATABASE DATA...");
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('environment_access')->truncate();
        DB::table('environments')->truncate();
        DB::table('user_definitions')->truncate();
        DB::table('definitions')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        echo ("[OK]\n");

        echo ("DELETING DEFINITIONS DATA...");
        if (Storage::exists('definitions')) {
            Storage::deleteDirectory('definitions');
        }
        echo ("[OK]\n");

        //------------------------------------------------------------
        echo ("-- USERS --\n");
        echo ("Adding user 'Admin User'...");
        $adm = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'A',
        ]);
        echo ("[OK]\n");

        echo ("ADDING USER 'Lecturer User'...");
        $lect = DB::table('users')->insertGetId([
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
        
        //------------------------------------------------------------
        echo ("-- CATEGORIES --\n");
        echo ("ADDING CATEGORY 'Web Vulnerabilities'...");
        $webVuln = DB::table('categories')->insertGetId([
            'name' => 'Web Vulnerabilities',
            'training_type' => 'R'
        ]);
        echo ("[OK]\n");
        
        echo ("ADDING CATEGORY 'Brute Forcing'...");
        $bruteForce = DB::table('categories')->insertGetId([
            'name' => 'Brute Forcing',
            'training_type' => 'B'
        ]);
        echo ("[OK]\n");

        echo ("ADDING CATEGORY 'Monitoring'...");
        DB::table('categories')->insert([
            'name' => 'Monitoring',
            'training_type' => 'B'
        ]);
        echo ("[OK]\n");
       
        //------------------------------------------------------------
        echo ("-- DEFINITIONS --\n");
        echo ("ADDING DEFINITION 'kali-juice-shop'...");

        $description = file_get_contents(__DIR__ . '/definitions/kali-juice-shop_description.html');
        $definition_file = new File(__DIR__ . "/definitions/kali-juice-shop.json");

        Storage::createDirectory('definitions');
        
        Storage::putFileAs('definitions', $definition_file,'kali-juice-shop.json');

        $kali_owasp_def = DB::table('definitions')->insertGetId([
            'name' => 'kali-juice-shop',
            'user_id' => $adm,
            'category_id' => $webVuln,
            'path' => "definitions/kali-juice-shop.json",
            'private' => 1,
            'description' => $description,
            'tags' => 'owasp-juice-shop,penetration-testing,kali-linux,vulnerability-assessment,web-application-security,ethical-hacking'
        ]);
        echo ("[OK]\n");
        
        echo ("ADDING DEFINITION 'owasp-juice-shop'...");

        $description = file_get_contents(__DIR__ . '/definitions/owasp-juice-shop_description.html');
        $definition_file = new File(__DIR__ . "/definitions/owasp-juice-shop.json");
        
        Storage::putFileAs('definitions', $definition_file,'owasp-juice-shop.json');

        $owasp_def = DB::table('definitions')->insertGetId([
            'name' => 'owasp-juice-shop',
            'user_id' => $adm,
            'category_id' => $webVuln,
            'path' => "definitions/owasp-juice-shop.json",
            'private' => 0,
            'description' => $description,
            'tags' => 'owasp-juice-shop,vulnerability-assessment,web-application-security,ethical-hacking'
        ]);
        echo ("[OK]\n");

        echo ("ADDING DEFINITION 'flask-ctf'...");

        $description = file_get_contents(__DIR__ . '/definitions/flask-ctf_description.html');
        $definition_file = new File(__DIR__ . "/definitions/flask-ctf.json");
        
        Storage::putFileAs('definitions', $definition_file,'flask-ctf.json');

        $ctf_def = DB::table('definitions')->insertGetId([
            'name' => 'flask-ctf',
            'user_id' => $lect,
            'category_id' => $webVuln,
            'path' => "definitions/flask-ctf.json",
            'private' => 0,
            'description' => $description,
            'tags' => 'flask-app,ctf,web-vulnerabilities'
        ]);

        echo ("[OK]\n");

        echo ("ADDING DEFINITION 'brute-force'...");

        $description = file_get_contents(__DIR__ . '/definitions/brute-force_description.html');
        $definition_file = new File(__DIR__ . "/definitions/brute-force.json");
        
        Storage::putFileAs('definitions', $definition_file,'brute-force.json');

        $brute_force_def = DB::table('definitions')->insertGetId([
            'name' => 'brute-force',
            'user_id' => $lect,
            'category_id' => $bruteForce,
            'path' => "definitions/brute-force.json",
            'private' => 0,
            'description' => $description,
            'tags' => 'hydra,medusa,kali,ssh,brute-forcing'
        ]);
        
        echo ("[OK]\n");
        echo ("\nSEEDING COMPLETE\n\n");

        //------------------------------------------------------------
        echo ("-- USER DEFINITIONS --\n");
        echo ("ASSOCIATING DEFINITION 'kali-juice-shop' to 'Admin User'...");
        DB::table('user_definitions')->insert([
            'user_id' => $adm,
            'definition_id' => $kali_owasp_def
        ]);
        echo ("[OK]\n");

        echo ("ASSOCIATING DEFINITION 'owasp-juice-shop' to 'Admin User'...");
        DB::table('user_definitions')->insert([
            'user_id' => $adm,
            'definition_id' => $owasp_def
        ]);
        echo ("[OK]\n");

        echo ("ASSOCIATING DEFINITION 'brute-force' to 'Admin User'...");
        DB::table('user_definitions')->insert([
            'user_id' => $adm,
            'definition_id' => $brute_force_def
        ]);
        echo ("[OK]\n");

        echo ("ASSOCIATING DEFINITION 'flask-ctf' to 'Lecturer User'...");
        DB::table('user_definitions')->insert([
            'user_id' => $lect,
            'definition_id' => $ctf_def
        ]);
        echo ("[OK]\n");

        echo ("ASSOCIATING DEFINITION 'brute-force' to 'Lecturer User'...");
        DB::table('user_definitions')->insert([
            'user_id' => $lect,
            'definition_id' => $brute_force_def
        ]);
        echo ("[OK]\n");
    }
}
