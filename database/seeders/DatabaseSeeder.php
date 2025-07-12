<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create main admin user
        $admin = User::create([
            'name' => 'Ива',
            'email' => 'iva@wuvu.com',
            'password' => Hash::make('uraqt'),
            'role' => 'admin',
            'status' => 'active',
            'department' => 'Boss Lady',
            'phone' => '0888622212',
            'hourly_rate' => 50.00,
            'hire_date' => now()->subMonths(24),
        ]);

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
            'status' => 'active',
        ]);
        // Create team members
        $teamMembers = [
            // [
            //     'name' => 'Daniel Marinov',
            //     'email' => 'r3s0lu7e@gmail.com',
            //     'role' => 'developer',
            //     'department' => 'Development',
            //     'phone' => '0878736802',
            //     'hourly_rate' => 45.00,
            // ],
            [
                'name' => 'Jane Designer',
                'email' => 'jane@wuvu.com',
                'role' => 'developer',
                'department' => 'Design',
                'phone' => '+1-555-0003',
                'hourly_rate' => 40.00,
            ],
            [
                'name' => 'Mike Tester',
                'email' => 'mike@wuvu.com',
                'role' => 'tester',
                'department' => 'Quality Assurance',
                'phone' => '+1-555-0004',
                'hourly_rate' => 35.00,
            ],
            [
                'name' => 'Sarah Manager',
                'email' => 'sarah@wuvu.com',
                'role' => 'project_manager',
                'department' => 'Management',
                'phone' => '+1-555-0005',
                'hourly_rate' => 55.00,
            ],
            [
                'name' => 'Client User',
                'email' => 'client@wuvu.com',
                'role' => 'admin',
                'department' => 'Business',
                'phone' => '+1-555-0006',
                'status' => 'active',
            ],
        ];

        $users = collect();
        foreach ($teamMembers as $memberData) {
            $user = User::create(array_merge([
                'password' => Hash::make('password'),
                'status' => 'active',
                'hire_date' => now()->subMonths(rand(3, 18)),
            ], $memberData));
            $users->push($user);
        }

        // Create sample projects
        $projects = [
            [
                'name' => 'E-commerce Platform',
                'description' => 'A modern e-commerce platform with advanced features',
                'key' => 'ECOM',
                'status' => 'active',
                'priority' => 'high',
                'color' => '#3B82F6',
                'start_date' => now()->subMonths(3),
                'end_date' => now()->addMonths(3),
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Cross-platform mobile application',
                'key' => 'MOBILE',
                'status' => 'active',
                'priority' => 'medium',
                'color' => '#10B981',
                'start_date' => now()->addWeeks(2),
                'end_date' => now()->addMonths(6),
            ],
            [
                'name' => 'Website Redesign',
                'description' => 'Complete redesign of the company website',
                'key' => 'WEB',
                'status' => 'active',
                'priority' => 'high',
                'color' => '#F59E0B',
                'start_date' => now()->subMonth(),
                'end_date' => now()->addMonth(),
            ],
        ];

        $projectModels = collect();
        foreach ($projects as $projectData) {
            $project = Project::create(array_merge([
                'owner_id' => $admin->id,
            ], $projectData));

            // Add team members to projects
            $project->members()->attach($users->random(rand(2, 4))->pluck('id'));
            $projectModels->push($project);
        }

        // Create sample tasks
        $taskTypes = ['story', 'bug', 'task', 'epic'];
        $taskStatuses = ['todo', 'in_progress', 'completed', 'blocked'];
        $taskPriorities = ['low', 'medium', 'high'];

        foreach ($projectModels as $project) {
            $projectMembers = $project->members->push($project->owner);

            for ($i = 0; $i < rand(8, 15); $i++) {
                $task = Task::create([
                    'title' => 'Task ' . ($i + 1) . ' for ' . $project->name,
                    'description' => 'This is a sample task description for ' . $project->name . '. It contains detailed information about what needs to be done.',
                    'type' => $taskTypes[array_rand($taskTypes)],
                    'status' => $taskStatuses[array_rand($taskStatuses)],
                    'priority' => $taskPriorities[array_rand($taskPriorities)],
                    'project_id' => $project->id,
                    'creator_id' => $admin->id,
                    'assignee_id' => $projectMembers->random()->id,
                    'due_date' => rand(0, 1) ? now()->addDays(rand(-30, 60)) : null,
                    'story_points' => rand(0, 1) ? rand(1, 13) : null,
                    'estimated_hours' => rand(0, 1) ? rand(1, 40) : null,
                ]);

                // Add some comments to tasks
                if (rand(0, 1)) {
                    for ($j = 0; $j < rand(1, 3); $j++) {
                        TaskComment::create([
                            'task_id' => $task->id,
                            'user_id' => $projectMembers->random()->id,
                            'content' => 'This is a sample comment for task ' . $task->title . '. It provides additional context or updates.',
                        ]);
                    }
                }
            }
        }
    }
}
