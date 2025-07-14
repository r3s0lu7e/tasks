<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskStatus;
use App\Models\TaskType;
use App\Models\PersonalNote;

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
        // User::create([
        //     'name' => 'Daniel Marinov',
        //     'email' => 'r3s0lu7e@gmail.com',
        //     'password' => Hash::make('12345'),
        //     'role' => 'developer',
        //     'department' => 'Development',
        //     'phone' => '0878736802',
        //     'hourly_rate' => 45.00,
        //     'hire_date' => '2024-07-1',
        //     'status' => 'active',
        // ]);
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
                'name' => 'Daniel Marinov',
                'email' => 'r3s0lu7e@gmail.com',
                'password' => Hash::make('12345'),
                'role' => 'admin',
                'department' => 'Development',
                'phone' => '0878736802',
                'hourly_rate' => 45.00,
                'hire_date' => '2024-07-1',
                'status' => 'active',
            ],
            [
                'name' => 'Jane Designer',
                'email' => 'jane@wuvu.com',
                'role' => 'developer',
                'department' => 'Design',
                'phone' => '+1-555-0003',
                'hourly_rate' => 40.00,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Mike Tester',
                'email' => 'mike@wuvu.com',
                'role' => 'tester',
                'department' => 'Quality Assurance',
                'phone' => '+1-555-0004',
                'hourly_rate' => 35.00,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Sarah Manager',
                'email' => 'sarah@wuvu.com',
                'role' => 'project_manager',
                'department' => 'Management',
                'phone' => '+1-555-0005',
                'hourly_rate' => 55.00,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Client User',
                'email' => 'client@wuvu.com',
                'role' => 'admin',
                'department' => 'Business',
                'phone' => '+1-555-0006',
                'status' => 'active',
                'password' => Hash::make('password'),
            ],
        ];

        $users = collect();
        foreach ($teamMembers as $memberData) {
            $user = User::create(array_merge([

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
            [
                'name' => 'CRM System',
                'description' => 'Customer Relationship Management system development',
                'key' => 'CRM',
                'status' => 'active',
                'priority' => 'high',
                'color' => '#EF4444',
                'start_date' => now()->subMonths(1),
                'end_date' => now()->addMonths(4),
            ],
            [
                'name' => 'Analytics Dashboard',
                'description' => 'Real-time analytics and reporting dashboard',
                'key' => 'ANALYTICS',
                'status' => 'active',
                'priority' => 'medium',
                'color' => '#8B5CF6',
                'start_date' => now()->addWeeks(1),
                'end_date' => now()->addMonths(2),
            ],
            [
                'name' => 'API Integration',
                'description' => 'Third-party API integration project',
                'key' => 'API',
                'status' => 'active',
                'priority' => 'high',
                'color' => '#EC4899',
                'start_date' => now()->subWeeks(2),
                'end_date' => now()->addMonths(1),
            ],
            [
                'name' => 'DevOps Infrastructure',
                'description' => 'Infrastructure modernization and automation',
                'key' => 'DEVOPS',
                'status' => 'active',
                'priority' => 'high',
                'color' => '#14B8A6',
                'start_date' => now(),
                'end_date' => now()->addMonths(5),
            ],
            [
                'name' => 'Security Audit',
                'description' => 'Comprehensive security audit and improvements',
                'key' => 'SEC',
                'status' => 'active',
                'priority' => 'critical',
                'color' => '#DC2626',
                'start_date' => now()->addWeek(),
                'end_date' => now()->addMonths(2),
            ],
            [
                'name' => 'AI Integration',
                'description' => 'AI and machine learning features implementation',
                'key' => 'AI',
                'status' => 'active',
                'priority' => 'high',
                'color' => '#6366F1',
                'start_date' => now()->addMonths(1),
                'end_date' => now()->addMonths(7),
            ],
            [
                'name' => 'Legacy System Migration',
                'description' => 'Migration of legacy systems to modern architecture',
                'key' => 'LEGACY',
                'status' => 'active',
                'priority' => 'high',
                'color' => '#F97316',
                'start_date' => now()->subWeeks(1),
                'end_date' => now()->addMonths(8),
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
        $taskTypes = TaskType::all();
        $taskStatuses = TaskStatus::all();
        $taskPriorities = ['low', 'medium', 'high'];

        // More varied task titles for realistic data
        $taskTitles = [
            'Implement user authentication system',
            'Fix login form validation',
            'Add responsive design for mobile',
            'Optimize database queries',
            'Create API documentation',
            'Setup automated testing',
            'Implement search functionality',
            'Add email notifications',
            'Fix security vulnerabilities',
            'Update user interface design',
            'Integrate payment gateway',
            'Add data export feature',
            'Implement caching system',
            'Create admin dashboard',
            'Add user profile management',
            'Fix cross-browser compatibility',
            'Implement file upload feature',
            'Add real-time notifications',
            'Create backup system',
            'Optimize application performance',
            'Add multi-language support',
            'Implement role-based access',
            'Create reporting system',
            'Add social media integration',
            'Fix memory leak issues',
            'Implement audit logging',
            'Add advanced search filters',
            'Create mobile application',
            'Implement data validation',
            'Add configuration management',
        ];

        foreach ($projectModels as $project) {
            $projectMembers = $project->members->push($project->owner);

            for ($i = 0; $i < 500; $i++) {
                $taskTitle = $taskTitles[array_rand($taskTitles)] . ' #' . ($i + 1);

                $task = Task::create([
                    'title' => $taskTitle,
                    'description' => 'This is a sample task description for ' . $project->name . '. Task: ' . $taskTitle . '. It contains detailed information about what needs to be done and includes specific requirements and acceptance criteria.',
                    'task_type_id' => $taskTypes->random()->id,
                    'task_status_id' => $taskStatuses->random()->id,
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

        // Create some personal notes
        PersonalNote::create([
            'user_id' => $admin->id,
            'title' => 'Q3 Marketing Strategy',
            'content' => 'Finalize the marketing strategy for the upcoming quarter. Focus on social media engagement and content marketing. Review the budget and allocate resources for key campaigns.',
            'color' => '#3B82F6',
            'is_pinned' => true,
        ]);

        PersonalNote::create([
            'user_id' => $admin->id,
            'title' => 'Meeting with a potential client',
            'content' => 'Prepare a presentation for the meeting with the new client. Highlight our key strengths and successful projects. Include a detailed proposal and timeline.',
            'color' => '#10B981',
            'is_favorite' => true,
        ]);

        if ($users->isNotEmpty()) {
            $randomUser = $users->random();
            PersonalNote::create([
                'user_id' => $randomUser->id,
                'title' => 'Ideas for the new project',
                'content' => 'Brainstorming session for the new mobile app. Think about the target audience, key features, and monetization strategy. Create a mood board for the UI/UX design.',
                'color' => '#F59E0B',
            ]);
        }
    }
}
