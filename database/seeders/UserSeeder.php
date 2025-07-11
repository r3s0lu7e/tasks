<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Ива',
            'email' => 'iva@jiraclone.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // // Create project manager
        // User::create([
        //     'name' => 'Project Manager',
        //     'email' => 'pm@jiraclone.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'project_manager',
        // ]);

        // // Create developers
        // User::create([
        //     'name' => 'John Developer',
        //     'email' => 'john@jiraclone.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'developer',
        // ]);

        // User::create([
        //     'name' => 'Jane Developer',
        //     'email' => 'jane@jiraclone.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'developer',
        // ]);

        // // Create tester
        // User::create([
        //     'name' => 'Test User',
        //     'email' => 'tester@jiraclone.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'tester',
        // ]);
    }
}
