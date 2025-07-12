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
        // User::create([
        //     'name' => 'Ива',
        //     'email' => 'iva@vkyshtistudio.com',
        //     'password' => Hash::make('uraqt'),
        //     'role' => 'admin',
        // ]);

        // Create developer
        User::create([
            'name' => 'Daniel Marinov',
            'email' => 'r3s0lu7e@gmail.com',
            'password' => Hash::make('12345'),
            'role' => 'developer',
            'department' => 'Development',
            'phone' => '0878736802',
            'hourly_rate' => 45.00,
            'hire_date' => '2024-07-1',
            'status' => 'Active',
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
