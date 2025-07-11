

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
                                    Tasks: <?php echo e($project->name); ?>

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
                            <a href="<?php echo e(route('projects.show', $project)); ?>"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Back to Project
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="<?php echo e(route('projects.tasks.index', $project)); ?>" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <input type="text" id="search" name="search" value="<?php echo e(request('search')); ?>"
                                       placeholder="Search tasks..."
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm">
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select id="status" name="status"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm">
                                    <option value="">All Statuses</option>
                                    <option value="todo" <?php echo e(request('status') == 'todo' ? 'selected' : ''); ?>>To Do
                                    </option>
                                    <option value="in_progress" <?php echo e(request('status') == 'in_progress' ? 'selected' : ''); ?>>
                                        In Progress</option>
                                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>
                                        Completed</option>
                                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>
                                        Cancelled</option>
                                </select>
                            </div>

                            <!-- Priority Filter -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                <select id="priority" name="priority"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm">
                                    <option value="">All Priorities</option>
                                    <option value="low" <?php echo e(request('priority') == 'low' ? 'selected' : ''); ?>>Low
                                    </option>
                                    <option value="medium" <?php echo e(request('priority') == 'medium' ? 'selected' : ''); ?>>Medium
                                    </option>
                                    <option value="high" <?php echo e(request('priority') == 'high' ? 'selected' : ''); ?>>High
                                    </option>
                                    <option value="critical" <?php echo e(request('priority') == 'critical' ? 'selected' : ''); ?>>
                                        Critical</option>
                                </select>
                            </div>

                            <!-- Type Filter -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select id="type" name="type"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm">
                                    <option value="">All Types</option>
                                    <option value="story" <?php echo e(request('type') == 'story' ? 'selected' : ''); ?>>Story
                                    </option>
                                    <option value="bug" <?php echo e(request('type') == 'bug' ? 'selected' : ''); ?>>Bug</option>
                                    <option value="task" <?php echo e(request('type') == 'task' ? 'selected' : ''); ?>>Task</option>
                                    <option value="epic" <?php echo e(request('type') == 'epic' ? 'selected' : ''); ?>>Epic</option>
                                </select>
                            </div>

                            <!-- Assignee Filter -->
                            <div>
                                <label for="assignee" class="block text-sm font-medium text-gray-700 mb-1">Assignee</label>
                                <select id="assignee" name="assignee"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm">
                                    <option value="">All Assignees</option>
                                    <option value="unassigned" <?php echo e(request('assignee') == 'unassigned' ? 'selected' : ''); ?>>
                                        Unassigned</option>
                                    <?php $__currentLoopData = $project->members->merge(collect([$project->owner]))->unique('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($member->id); ?>"
                                                <?php echo e(request('assignee') == $member->id ? 'selected' : ''); ?>>
                                            <?php echo e($member->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Apply Filters
                            </button>
                            <a href="<?php echo e(route('projects.tasks.index', $project)); ?>"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tasks Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <?php if($tasks->count() > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Task</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Type</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Priority</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Assignee</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Due Date</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Created</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                         class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-full text-xs font-medium
                                                    <?php if($task->type === 'story'): ?> bg-green-100 text-green-800
                                                    <?php elseif($task->type === 'bug'): ?> bg-red-100 text-red-800
                                                    <?php elseif($task->type === 'epic'): ?> bg-purple-100 text-purple-800
                                                    <?php else: ?> bg-blue-100 text-blue-800 <?php endif; ?>">
                                                        <?php echo e(strtoupper(substr($task->type, 0, 1))); ?>

                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <a href="<?php echo e(route('projects.tasks.show', [$project, $task])); ?>"
                                                               class="hover:text-jira-blue">
                                                                <?php echo e($task->title); ?>

                                                            </a>
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            <?php echo e($project->key); ?>-<?php echo e($task->id); ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                <?php if($task->type === 'story'): ?> bg-green-100 text-green-800
                                                <?php elseif($task->type === 'bug'): ?> bg-red-100 text-red-800
                                                <?php elseif($task->type === 'epic'): ?> bg-purple-100 text-purple-800
                                                <?php else: ?> bg-blue-100 text-blue-800 <?php endif; ?>">
                                                    <?php echo e(ucfirst($task->type)); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                <?php if($task->status === 'completed'): ?> bg-green-100 text-green-800
                                                <?php elseif($task->status === 'in_progress'): ?> bg-blue-100 text-blue-800
                                                <?php elseif($task->status === 'cancelled'): ?> bg-red-100 text-red-800
                                                <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $task->status))); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                <?php if($task->priority === 'critical'): ?> bg-red-100 text-red-800
                                                <?php elseif($task->priority === 'high'): ?> bg-orange-100 text-orange-800
                                                <?php elseif($task->priority === 'medium'): ?> bg-yellow-100 text-yellow-800
                                                <?php else: ?> bg-green-100 text-green-800 <?php endif; ?>">
                                                    <?php echo e(ucfirst($task->priority)); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php if($task->assignee): ?>
                                                    <div class="flex items-center">
                                                        <div
                                                             class="flex-shrink-0 h-6 w-6 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-xs font-medium text-gray-700">
                                                                <?php echo e(strtoupper(substr($task->assignee->name, 0, 2))); ?>

                                                            </span>
                                                        </div>
                                                        <div class="ml-2"><?php echo e($task->assignee->name); ?></div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-gray-400">Unassigned</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php if($task->due_date): ?>
                                                    <div class="<?php if($task->due_date < now() && !in_array($task->status, ['completed', 'cancelled'])): ?> text-red-600 <?php endif; ?>">
                                                        <?php echo e($task->due_date->format('M j, Y')); ?>

                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-gray-400">No due date</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo e($task->created_at->format('M j, Y')); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-2">
                                                    <a href="<?php echo e(route('projects.tasks.show', [$project, $task])); ?>"
                                                       class="text-jira-blue hover:text-blue-700">View</a>
                                                    <a href="<?php echo e(route('projects.tasks.edit', [$project, $task])); ?>"
                                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            <?php echo e($tasks->appends(request()->query())->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new task.</p>
                            <div class="mt-6">
                                <a href="<?php echo e(route('projects.tasks.create', $project)); ?>"
                                   class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Create Task
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\d\HomeProjects\tasksIveto\resources\views/tasks/index.blade.php ENDPATH**/ ?>