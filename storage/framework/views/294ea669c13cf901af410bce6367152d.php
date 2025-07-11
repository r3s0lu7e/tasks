

<?php $__env->startSection('content'); ?>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Project Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-4 h-4 rounded" style="background-color: <?php echo e($project->color); ?>"></div>
                            <div>
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                    <?php echo e($project->name); ?>

                                </h2>
                                <p class="text-sm text-gray-600"><?php echo e($project->key); ?></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="<?php echo e(route('projects.tasks.create', $project)); ?>"
                               class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Task
                            </a>
                            <?php if($project->owner_id === auth()->id() || auth()->user()->isAdmin()): ?>
                                <a href="<?php echo e(route('projects.edit', $project)); ?>"
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Edit Project
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('projects.index')); ?>"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Back to Projects
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Project Details -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <!-- Project Info -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Status</h3>
                            <span
                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            <?php if($project->status === 'active'): ?> bg-green-100 text-green-800
                            <?php elseif($project->status === 'planning'): ?> bg-yellow-100 text-yellow-800
                            <?php elseif($project->status === 'on_hold'): ?> bg-orange-100 text-orange-800
                            <?php elseif($project->status === 'completed'): ?> bg-blue-100 text-blue-800
                            <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $project->status))); ?>

                            </span>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Priority</h3>
                            <span
                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            <?php if($project->priority === 'critical'): ?> bg-red-100 text-red-800
                            <?php elseif($project->priority === 'high'): ?> bg-orange-100 text-orange-800
                            <?php elseif($project->priority === 'medium'): ?> bg-yellow-100 text-yellow-800
                            <?php else: ?> bg-green-100 text-green-800 <?php endif; ?>">
                                <?php echo e(ucfirst($project->priority)); ?>

                            </span>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Owner</h3>
                            <p class="text-sm text-gray-900"><?php echo e($project->owner->name); ?></p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Members</h3>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-900"><?php echo e($project->members->count() + 1); ?></span>
                                <a href="<?php echo e(route('projects.members', $project)); ?>"
                                   class="text-xs text-jira-blue hover:text-blue-700">
                                    Manage
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php if($project->description): ?>
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                            <p class="text-gray-700"><?php echo e($project->description); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Project Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600"><?php echo e($stats['total_tasks']); ?></div>
                            <div class="text-sm text-blue-600">Total Tasks</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600"><?php echo e($stats['completed_tasks']); ?></div>
                            <div class="text-sm text-green-600">Completed</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600"><?php echo e($stats['in_progress_tasks']); ?></div>
                            <div class="text-sm text-yellow-600">In Progress</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-gray-600"><?php echo e($stats['todo_tasks']); ?></div>
                            <div class="text-sm text-gray-600">To Do</div>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-red-600"><?php echo e($stats['overdue_tasks']); ?></div>
                            <div class="text-sm text-red-600">Overdue</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kanban Board -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg text-gray-800">Tasks</h3>
                        <div class="flex items-center space-x-3">
                            <a href="<?php echo e(route('projects.tasks.index', $project)); ?>"
                               class="text-sm text-jira-blue hover:text-blue-700">
                                View All Tasks
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- To Do Column -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                                To Do
                                <span class="ml-auto bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full">
                                    <?php echo e($tasksByStatus->get('todo', collect())->count()); ?>

                                </span>
                            </h4>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $tasksByStatus->get('todo', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div
                                         class="bg-white p-3 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                        <a href="<?php echo e(route('projects.tasks.show', [$project, $task])); ?>" class="block">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h5 class="text-sm font-medium text-gray-900 mb-1"><?php echo e($task->title); ?>

                                                    </h5>
                                                    <p class="text-xs text-gray-500 mb-2"><?php echo e($task->type); ?> •
                                                        <?php echo e($project->key); ?>-<?php echo e($task->id); ?></p>
                                                    <div class="flex items-center space-x-2">
                                                        <span
                                                              class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                        <?php if($task->priority === 'critical'): ?> bg-red-100 text-red-800
                                                        <?php elseif($task->priority === 'high'): ?> bg-orange-100 text-orange-800
                                                        <?php elseif($task->priority === 'medium'): ?> bg-yellow-100 text-yellow-800
                                                        <?php else: ?> bg-green-100 text-green-800 <?php endif; ?>">
                                                            <?php echo e(ucfirst($task->priority)); ?>

                                                        </span>
                                                        <?php if($task->assignee): ?>
                                                            <span
                                                                  class="text-xs text-gray-500"><?php echo e($task->assignee->name); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <!-- In Progress Column -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                <span class="w-3 h-3 bg-blue-400 rounded-full mr-2"></span>
                                In Progress
                                <span class="ml-auto bg-blue-200 text-blue-600 text-xs px-2 py-1 rounded-full">
                                    <?php echo e($tasksByStatus->get('in_progress', collect())->count()); ?>

                                </span>
                            </h4>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $tasksByStatus->get('in_progress', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div
                                         class="bg-white p-3 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                        <a href="<?php echo e(route('projects.tasks.show', [$project, $task])); ?>" class="block">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h5 class="text-sm font-medium text-gray-900 mb-1"><?php echo e($task->title); ?>

                                                    </h5>
                                                    <p class="text-xs text-gray-500 mb-2"><?php echo e($task->type); ?> •
                                                        <?php echo e($project->key); ?>-<?php echo e($task->id); ?></p>
                                                    <div class="flex items-center space-x-2">
                                                        <span
                                                              class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                        <?php if($task->priority === 'critical'): ?> bg-red-100 text-red-800
                                                        <?php elseif($task->priority === 'high'): ?> bg-orange-100 text-orange-800
                                                        <?php elseif($task->priority === 'medium'): ?> bg-yellow-100 text-yellow-800
                                                        <?php else: ?> bg-green-100 text-green-800 <?php endif; ?>">
                                                            <?php echo e(ucfirst($task->priority)); ?>

                                                        </span>
                                                        <?php if($task->assignee): ?>
                                                            <span
                                                                  class="text-xs text-gray-500"><?php echo e($task->assignee->name); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <!-- Completed Column -->
                        <div class="bg-green-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                                Completed
                                <span class="ml-auto bg-green-200 text-green-600 text-xs px-2 py-1 rounded-full">
                                    <?php echo e($tasksByStatus->get('completed', collect())->count()); ?>

                                </span>
                            </h4>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $tasksByStatus->get('completed', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div
                                         class="bg-white p-3 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                        <a href="<?php echo e(route('projects.tasks.show', [$project, $task])); ?>" class="block">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h5 class="text-sm font-medium text-gray-900 mb-1"><?php echo e($task->title); ?>

                                                    </h5>
                                                    <p class="text-xs text-gray-500 mb-2"><?php echo e($task->type); ?> •
                                                        <?php echo e($project->key); ?>-<?php echo e($task->id); ?></p>
                                                    <div class="flex items-center space-x-2">
                                                        <span
                                                              class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                        <?php if($task->priority === 'critical'): ?> bg-red-100 text-red-800
                                                        <?php elseif($task->priority === 'high'): ?> bg-orange-100 text-orange-800
                                                        <?php elseif($task->priority === 'medium'): ?> bg-yellow-100 text-yellow-800
                                                        <?php else: ?> bg-green-100 text-green-800 <?php endif; ?>">
                                                            <?php echo e(ucfirst($task->priority)); ?>

                                                        </span>
                                                        <?php if($task->assignee): ?>
                                                            <span
                                                                  class="text-xs text-gray-500"><?php echo e($task->assignee->name); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <!-- Cancelled Column -->
                        <div class="bg-red-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                                Cancelled
                                <span class="ml-auto bg-red-200 text-red-600 text-xs px-2 py-1 rounded-full">
                                    <?php echo e($tasksByStatus->get('cancelled', collect())->count()); ?>

                                </span>
                            </h4>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $tasksByStatus->get('cancelled', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div
                                         class="bg-white p-3 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                        <a href="<?php echo e(route('projects.tasks.show', [$project, $task])); ?>" class="block">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h5 class="text-sm font-medium text-gray-900 mb-1"><?php echo e($task->title); ?>

                                                    </h5>
                                                    <p class="text-xs text-gray-500 mb-2"><?php echo e($task->type); ?> •
                                                        <?php echo e($project->key); ?>-<?php echo e($task->id); ?></p>
                                                    <div class="flex items-center space-x-2">
                                                        <span
                                                              class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                        <?php if($task->priority === 'critical'): ?> bg-red-100 text-red-800
                                                        <?php elseif($task->priority === 'high'): ?> bg-orange-100 text-orange-800
                                                        <?php elseif($task->priority === 'medium'): ?> bg-yellow-100 text-yellow-800
                                                        <?php else: ?> bg-green-100 text-green-800 <?php endif; ?>">
                                                            <?php echo e(ucfirst($task->priority)); ?>

                                                        </span>
                                                        <?php if($task->assignee): ?>
                                                            <span
                                                                  class="text-xs text-gray-500"><?php echo e($task->assignee->name); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\d\HomeProjects\tasksIveto\resources\views/projects/show.blade.php ENDPATH**/ ?>