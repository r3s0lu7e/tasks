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
     * Configuration variables for seeding
     */
    private int $numberOfProjects = 300;
    private int $tasksPerProject = 1000;

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

        // Create more team members for better distribution across projects
        $teamMembers = [
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
            // Additional team members for projects
            [
                'name' => 'Alex Frontend',
                'email' => 'alex@wuvu.com',
                'role' => 'developer',
                'department' => 'Frontend',
                'phone' => '+1-555-0007',
                'hourly_rate' => 42.00,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Emma Backend',
                'email' => 'emma@wuvu.com',
                'role' => 'developer',
                'department' => 'Backend',
                'phone' => '+1-555-0008',
                'hourly_rate' => 48.00,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'David DevOps',
                'email' => 'david@wuvu.com',
                'role' => 'developer',
                'department' => 'DevOps',
                'phone' => '+1-555-0009',
                'hourly_rate' => 52.00,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Lisa QA',
                'email' => 'lisa@wuvu.com',
                'role' => 'tester',
                'department' => 'Quality Assurance',
                'phone' => '+1-555-0010',
                'hourly_rate' => 38.00,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Tom PM',
                'email' => 'tom@wuvu.com',
                'role' => 'project_manager',
                'department' => 'Management',
                'phone' => '+1-555-0011',
                'hourly_rate' => 58.00,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Anna UX',
                'email' => 'anna@wuvu.com',
                'role' => 'developer',
                'department' => 'UX/UI',
                'phone' => '+1-555-0012',
                'hourly_rate' => 44.00,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Chris Mobile',
                'email' => 'chris@wuvu.com',
                'role' => 'developer',
                'department' => 'Mobile',
                'phone' => '+1-555-0013',
                'hourly_rate' => 46.00,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Rachel Data',
                'email' => 'rachel@wuvu.com',
                'role' => 'developer',
                'department' => 'Data Science',
                'phone' => '+1-555-0014',
                'hourly_rate' => 50.00,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Kevin Security',
                'email' => 'kevin@wuvu.com',
                'role' => 'developer',
                'department' => 'Security',
                'phone' => '+1-555-0015',
                'hourly_rate' => 55.00,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Sophia AI',
                'email' => 'sophia@wuvu.com',
                'role' => 'developer',
                'department' => 'AI/ML',
                'phone' => '+1-555-0016',
                'hourly_rate' => 60.00,
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

        // Create projects with varied data
        $projectTypes = [
            'E-commerce Platform',
            'Mobile App Development',
            'Website Redesign',
            'CRM System',
            'Analytics Dashboard',
            'API Integration',
            'DevOps Infrastructure',
            'Security Audit',
            'AI Integration',
            'Legacy System Migration',
            'Database Optimization',
            'Cloud Migration',
            'Payment System',
            'Social Media Platform',
            'Learning Management System',
            'Inventory System',
            'Customer Support Portal',
            'Marketing Automation',
            'Business Intelligence',
            'Content Management',
            'HR Management System',
            'Financial Dashboard',
            'Supply Chain Management',
            'Healthcare Portal',
            'Real Estate Platform',
            'Travel Booking System',
            'Food Delivery App',
            'Fitness Tracker',
            'Gaming Platform',
            'Video Streaming Service',
            'IoT Dashboard',
            'Blockchain Application',
            'Cryptocurrency Exchange',
            'Virtual Reality App',
            'Augmented Reality Tool',
            'Machine Learning Model',
            'Chatbot System',
            'Document Management',
            'Project Management Tool',
            'Time Tracking System',
            'Collaboration Platform',
            'Email Marketing Tool',
            'Survey Platform',
            'Event Management System',
            'Booking System',
            'Ticketing Platform',
            'Monitoring Dashboard',
            'Reporting System',
            'Workflow Automation',
            'Data Visualization Tool'
        ];

        $statuses = ['planning', 'active', 'on_hold', 'completed', 'cancelled'];
        $priorities = ['low', 'medium', 'high', 'critical'];
        $colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6', '#DC2626', '#6366F1', '#F97316'];

        $projectModels = collect();

        echo "Creating {$this->numberOfProjects} projects...\n";

        for ($i = 0; $i < $this->numberOfProjects; $i++) {
            $projectTypeIndex = $i % count($projectTypes);
            $projectName = $projectTypes[$projectTypeIndex] . ' ' . ($i + 1);
            $projectKey = 'PROJ' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);

            $project = Project::create([
                'name' => $projectName,
                'description' => 'Advanced ' . strtolower($projectTypes[$projectTypeIndex]) . ' with modern features and scalable architecture. This project includes comprehensive planning, development, testing, and deployment phases.',
                'key' => $projectKey,
                'status' => $statuses[array_rand($statuses)],
                'priority' => $priorities[array_rand($priorities)],
                'color' => $colors[array_rand($colors)],
                'owner_id' => $admin->id,
                'start_date' => now()->subDays(rand(0, 180)),
                'end_date' => now()->addDays(rand(30, 365)),
            ]);

            // Add team members to projects (3-8 members per project)
            $memberCount = rand(3, 8);
            $projectMembers = $users->random(min($memberCount, $users->count()));
            $project->members()->attach($projectMembers->pluck('id'));

            $projectModels->push($project);

            if (($i + 1) % 50 == 0) {
                echo "Created " . ($i + 1) . " projects...\n";
            }
        }

        // Get task types and statuses
        $taskTypes = TaskType::all();
        $taskStatuses = TaskStatus::all();
        $taskPriorities = ['low', 'medium', 'high', 'critical'];

        // Expanded task titles for more variety
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
            'Setup CI/CD pipeline',
            'Implement microservices architecture',
            'Add containerization support',
            'Create monitoring dashboard',
            'Implement load balancing',
            'Add SSL certificate',
            'Setup database replication',
            'Implement data encryption',
            'Add API rate limiting',
            'Create error handling system',
            'Implement session management',
            'Add two-factor authentication',
            'Create webhook system',
            'Implement queue processing',
            'Add image optimization',
            'Create content delivery network',
            'Implement search engine optimization',
            'Add analytics tracking',
            'Create user feedback system',
            'Implement A/B testing framework',
            'Add performance monitoring',
            'Create automated deployment',
            'Implement health checks',
            'Add logging aggregation',
            'Create disaster recovery plan',
            'Implement data migration tools',
            'Add feature flags system',
            'Create documentation portal',
            'Implement code review process',
            'Add security scanning',
            'Create integration tests',
            'Implement user onboarding',
            'Add email templates',
            'Create mobile responsive layout',
            'Implement dark mode support',
            'Add keyboard shortcuts',
            'Create accessibility features',
            'Implement progressive web app',
            'Add offline functionality',
            'Create push notifications',
            'Implement geolocation features',
            'Add calendar integration',
            'Create export functionality',
            'Implement batch operations',
            'Add custom themes',
            'Create widget system',
            'Implement plugin architecture',
            'Add third-party integrations',
            'Create automated backups',
            'Implement version control',
            'Add collaboration features',
            'Create real-time chat',
            'Implement video conferencing',
            'Add screen sharing',
            'Create file sharing system',
            'Implement task automation',
            'Add workflow management',
            'Create approval processes',
            'Implement notification center',
            'Add activity feeds',
            'Create timeline view',
            'Implement kanban board',
            'Add gantt chart',
            'Create resource management',
            'Implement time tracking',
            'Add expense tracking',
            'Create invoice generation',
            'Implement payment processing',
            'Add subscription management',
            'Create customer portal',
            'Implement support ticket system',
            'Add knowledge base',
            'Create FAQ system',
            'Implement live chat support',
        ];

        echo "Creating tasks for {$this->numberOfProjects} projects ({$this->tasksPerProject} tasks each)...\n";

        // Create tasks for each project
        foreach ($projectModels as $index => $project) {
            $projectMembers = $project->members->push($project->owner);

            // Use batch insert for better performance
            $tasks = [];
            $now = now();

            for ($i = 0; $i < $this->tasksPerProject; $i++) {
                $taskTitle = $taskTitles[array_rand($taskTitles)] . ' #' . ($i + 1);

                $tasks[] = [
                    'title' => $taskTitle,
                    'description' => 'This is a comprehensive task description for ' . $project->name . '. Task: ' . $taskTitle . '. It includes detailed requirements, acceptance criteria, and implementation guidelines. The task is part of the overall project roadmap and contributes to the successful delivery of the project objectives.',
                    'task_type_id' => $taskTypes->random()->id,
                    'task_status_id' => $taskStatuses->random()->id,
                    'priority' => $taskPriorities[array_rand($taskPriorities)],
                    'project_id' => $project->id,
                    'creator_id' => $admin->id,
                    'assignee_id' => $projectMembers->random()->id,
                    'due_date' => rand(0, 1) ? now()->addDays(rand(-30, 90))->format('Y-m-d') : null,
                    'story_points' => rand(0, 1) ? rand(1, 13) : null,
                    'estimated_hours' => rand(0, 1) ? rand(1, 40) : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Insert tasks in batches of 100 for better performance
            $taskChunks = array_chunk($tasks, 100);
            foreach ($taskChunks as $chunk) {
                Task::insert($chunk);
            }

            echo "Created {$this->tasksPerProject} tasks for project " . ($index + 1) . ": " . $project->name . "\n";
        }

        echo "Creating task comments...\n";

        // Add some comments to random tasks (reduced for memory efficiency)
        $commentCount = 0;
        $taskIds = Task::pluck('id')->random(min(5000, Task::count()));

        foreach ($taskIds as $taskId) {
            $task = Task::with('project.members', 'project.owner')->find($taskId);
            $projectMembers = $task->project->members->push($task->project->owner);

            for ($j = 0; $j < rand(1, 2); $j++) {
                TaskComment::create([
                    'task_id' => $task->id,
                    'user_id' => $projectMembers->random()->id,
                    'content' => 'This is a sample comment for task ' . $task->title . '. It provides additional context, updates, or clarifications about the task progress and requirements.',
                ]);
                $commentCount++;
            }
        }

        echo "Created $commentCount task comments.\n";

        // Create 100 personal notes for various users
        echo "Creating 100 personal notes...\n";

        $noteTypes = [
            'Meeting Notes',
            'Project Ideas',
            'Technical Research',
            'Client Feedback',
            'Team Updates',
            'Personal Tasks',
            'Learning Goals',
            'Code Snippets',
            'Bug Reports',
            'Feature Requests',
            'Performance Notes',
            'Security Concerns',
            'Database Queries',
            'API Documentation',
            'Design Concepts',
            'User Stories',
            'Testing Scenarios',
            'Deployment Notes',
            'Configuration Settings',
            'Troubleshooting',
            'Best Practices',
            'Code Reviews',
            'Architecture Decisions',
            'Sprint Planning',
            'Retrospective Notes',
            'Daily Standup',
            'Client Requirements',
            'Budget Planning',
            'Resource Allocation',
            'Risk Assessment',
            'Quality Assurance',
            'Documentation',
            'Training Materials',
            'Onboarding Checklist',
            'Process Improvements',
            'Tool Evaluations',
            'Performance Metrics',
            'Analytics Insights',
            'Marketing Strategy',
            'Sales Notes',
            'Customer Support',
            'Product Roadmap',
            'Competitive Analysis',
            'Market Research',
            'Innovation Ideas',
            'Efficiency Tips',
            'Workflow Optimization',
            'Team Building',
            'Knowledge Sharing',
            'Skill Development',
            'Career Planning',
            'Goal Setting'
        ];

        $noteContents = [
            'Important points from today\'s meeting with the development team. We discussed the upcoming sprint planning and resource allocation for the next quarter.',
            'Brainstorming session results for the new feature implementation. Key considerations include user experience, technical feasibility, and timeline constraints.',
            'Research findings on the latest technology trends and their potential impact on our current projects. Need to evaluate adoption strategies.',
            'Client feedback compilation from the recent product demo. Overall positive response with some specific enhancement requests.',
            'Weekly team progress update including completed tasks, current blockers, and upcoming priorities for the development cycle.',
            'Personal development goals for the current quarter including skill enhancement, certification targets, and learning objectives.',
            'Technical documentation for the new API endpoints including request/response formats, authentication requirements, and error handling.',
            'Code review notes highlighting best practices, potential improvements, and architectural considerations for the codebase.',
            'Bug investigation results with root cause analysis, reproduction steps, and proposed solutions for the reported issues.',
            'Feature specification document outlining requirements, acceptance criteria, and implementation approach for the new functionality.',
            'Performance optimization strategies including database query improvements, caching mechanisms, and resource utilization enhancements.',
            'Security audit findings with vulnerability assessments, risk ratings, and recommended mitigation strategies.',
            'Database schema changes documentation including migration scripts, data integrity checks, and rollback procedures.',
            'User interface design concepts with wireframes, user flow diagrams, and accessibility considerations.',
            'Testing strategy document covering unit tests, integration tests, and end-to-end testing scenarios.',
            'Deployment checklist including environment preparation, configuration updates, and post-deployment verification steps.',
            'Architecture decision record documenting technical choices, trade-offs, and rationale for the selected approach.',
            'Sprint retrospective insights including what went well, areas for improvement, and action items for the next iteration.',
            'Client requirements analysis with detailed specifications, priority rankings, and implementation timeline estimates.',
            'Budget planning notes including resource costs, timeline estimates, and risk factors for the project execution.',
        ];

        $colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6', '#DC2626', '#6366F1', '#F97316'];
        $allUsers = collect([$admin])->merge($users);

        for ($i = 0; $i < 100; $i++) {
            $noteType = $noteTypes[array_rand($noteTypes)];
            $noteContent = $noteContents[array_rand($noteContents)];

            PersonalNote::create([
                'user_id' => $allUsers->random()->id,
                'title' => $noteType . ' #' . ($i + 1),
                'content' => $noteContent . ' Note created on ' . now()->format('d.m.Y H:i') . ' for reference and follow-up actions.',
                'color' => $colors[array_rand($colors)],
                'is_pinned' => rand(0, 1) ? true : false,
                'is_favorite' => rand(0, 1) ? true : false,
            ]);
        }

        echo "Database seeding completed successfully!\n";
        echo "Created:\n";
        echo "- {$this->numberOfProjects} projects\n";
        echo "- " . ($this->numberOfProjects * $this->tasksPerProject) . " tasks ({$this->tasksPerProject} per project)\n";
        echo "- 100 personal notes\n";
        echo "- " . ($users->count() + 1) . " users\n";
        echo "- Task comments for better realism\n";
    }
}
