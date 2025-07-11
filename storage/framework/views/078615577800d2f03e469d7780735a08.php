

<?php $__env->startSection('content'); ?>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold">Welcome back, <?php echo e(Auth::user()->name); ?>!</h1>
                            <p class="text-blue-100 mt-1">Manage your team and track project progress</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="<?php echo e(route('projects.create')); ?>"
                               class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                New Project
                            </a>
                            <a href="<?php echo e(route('tasks.create')); ?>"
                               class="bg-white text-purple-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                New Task
                            </a>
                            <a href="<?php echo e(route('team.create')); ?>"
                               class="bg-white text-green-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                                Add Team Member
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-blue-500">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Projects</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo e($stats['total_projects']); ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-purple-500">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Tasks</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo e($stats['total_tasks']); ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-green-500">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo e($stats['completed_tasks']); ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-red-500">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Overdue</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo e($stats['overdue_tasks']); ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-yellow-500">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Due Today</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo e($stats['today_tasks']); ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-indigo-500">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Team Members</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo e($stats['team_members']); ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-teal-500">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-teal-500" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Active</dt>
                                    <dd class="text-lg font-medium text-gray-900"><?php echo e($stats['active_team_members']); ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overdue Tasks Alert -->
            <?php if($overdueTasks->count() > 0): ?>
                <div class="mb-8 bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                      clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                ⚠️ <?php echo e($overdueTasks->count()); ?> overdue <?php echo e(Str::plural('task', $overdueTasks->count())); ?>

                                need attention
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <?php $__currentLoopData = $overdueTasks->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="hover:underline">
                                                <?php echo e($task->title); ?>

                                            </a>
                                            <?php if($task->assignee): ?>
                                                (<?php echo e($task->assignee->name); ?>)
                                            <?php endif; ?>
                                            - Due: <?php echo e($task->due_date->format('M j, Y')); ?>

                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($overdueTasks->count() > 3): ?>
                                        <li>And <?php echo e($overdueTasks->count() - 3); ?> more...</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Team Members Overview -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Team Members</h3>
                            <a href="<?php echo e(route('team.index')); ?>" class="text-sm text-blue-600 hover:text-blue-800">View
                                all</a>
                        </div>
                        <div class="space-y-3">
                            <?php $__empty_1 = true; $__currentLoopData = $teamMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div
                                     class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <div
                                             class="h-8 w-8 rounded-full bg-<?php echo e($member->status_color); ?>-500 flex items-center justify-center">
                                            <span class="text-white text-sm font-medium"><?php echo e($member->initials); ?></span>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                <a href="<?php echo e(route('team.show', $member)); ?>" class="hover:text-blue-600">
                                                    <?php echo e($member->name); ?>

                                                </a>
                                            </h4>
                                            <p class="text-xs text-gray-500"><?php echo e($member->role); ?> •
                                                <?php echo e($member->position); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span
                                              class="text-sm font-medium text-gray-900"><?php echo e($member->assigned_tasks_count); ?></span>
                                        <p class="text-xs text-gray-500">tasks</p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-6">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No team members</h3>
                                    <p class="mt-1 text-sm text-gray-500">Add team members to start tracking their work.
                                    </p>
                                    <div class="mt-6">
                                        <a href="<?php echo e(route('team.create')); ?>" class="btn btn-primary">
                                            Add Team Member
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Active Projects -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Active Projects</h3>
                            <a href="<?php echo e(route('projects.index')); ?>"
                               class="text-sm text-blue-600 hover:text-blue-800">View all</a>
                        </div>
                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div
                                     class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3"
                                             style="background-color: <?php echo e($project->color); ?>"></div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">
                                                <a href="<?php echo e(route('projects.show', $project)); ?>"
                                                   class="hover:text-blue-600">
                                                    <?php echo e($project->name); ?>

                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-500"><?php echo e($project->tasks->count()); ?> tasks •
                                                <?php echo e($project->members->count()); ?> members</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span
                                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?php echo e($project->status === 'active' ? 'green' : 'gray'); ?>-100 text-<?php echo e($project->status === 'active' ? 'green' : 'gray'); ?>-800">
                                                <?php echo e(ucfirst($project->status)); ?>

                                            </span>
                                        </div>
                                        <div class="w-20 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full"
                                                 style="width: <?php echo e($project->progress); ?>%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo e($project->progress); ?>% complete</p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-6">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No projects yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Create your first project to get started.</p>
                                    <div class="mt-6">
                                        <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-primary">
                                            Create Project
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Activity</h3>
                            <a href="<?php echo e(route('tasks.index')); ?>" class="text-sm text-blue-600 hover:text-blue-800">View
                                all</a>
                        </div>
                        <div class="space-y-3">
                            <?php $__empty_1 = true; $__currentLoopData = $recentActivity->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div
                                     class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 rounded-full mr-3"
                                             style="background-color: <?php echo e($task->project->color); ?>"></div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">
                                                <a href="<?php echo e(route('tasks.show', $task)); ?>" class="hover:text-blue-600">
                                                    <?php echo e(Str::limit($task->title, 25)); ?>

                                                </a>
                                            </h4>
                                            <p class="text-xs text-gray-500">
                                                <?php echo e($task->project->name); ?>

                                                <?php if($task->assignee): ?>
                                                    • <?php echo e($task->assignee->name); ?>

                                                <?php endif; ?>
                                            </p>
                                            <p class="text-xs text-gray-500"><?php echo e($task->updated_at->diffForHumans()); ?></p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end space-y-1">
                                        <span class="status-badge status-<?php echo e(str_replace('_', '-', $task->status)); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $task->status))); ?>

                                        </span>
                                        <span class="priority-badge priority-<?php echo e($task->priority); ?>">
                                            <?php echo e(ucfirst($task->priority)); ?>

                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-6">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No recent activity</h3>
                                    <p class="mt-1 text-sm text-gray-500">Task activity will appear here.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Performance Overview -->
            <?php if($teamWorkload->count() > 0): ?>
                <div class="mt-8 bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Team Performance Overview</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <?php $__currentLoopData = $teamWorkload; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="text-center">
                                    <div
                                         class="h-16 w-16 mx-auto rounded-full bg-<?php echo e($member['status'] === 'active' ? 'green' : 'gray'); ?>-500 flex items-center justify-center mb-2">
                                        <span class="text-white font-medium"><?php echo e(substr($member['name'], 0, 2)); ?></span>
                                    </div>
                                    <h4 class="text-sm font-medium text-gray-900"><?php echo e($member['name']); ?></h4>
                                    <div class="mt-2 space-y-1">
                                        <div class="flex justify-between text-xs">
                                            <span>Workload:</span>
                                            <span class="font-medium"><?php echo e($member['workload']); ?> tasks</span>
                                        </div>
                                        <div class="flex justify-between text-xs">
                                            <span>Completion:</span>
                                            <span class="font-medium"><?php echo e($member['completion_rate']); ?>%</span>
                                        </div>
                                        <?php if($member['overdue_count'] > 0): ?>
                                            <div class="flex justify-between text-xs text-red-600">
                                                <span>Overdue:</span>
                                                <span class="font-medium"><?php echo e($member['overdue_count']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\d\HomeProjects\tasksIveto\resources\views/dashboard.blade.php ENDPATH**/ ?>