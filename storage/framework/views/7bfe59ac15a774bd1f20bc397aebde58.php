

<?php $__env->startSection('content'); ?>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            All Tasks
                        </h2>
                        <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary">
                            Create New Task
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="<?php echo e(route('tasks.index')); ?>" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>"
                                   placeholder="Search tasks..."
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="project" class="block text-sm font-medium text-gray-700">Project</label>
                            <select name="project" id="project"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">All Projects</option>
                                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($project->id); ?>"
                                            <?php echo e(request('project') == $project->id ? 'selected' : ''); ?>>
                                        <?php echo e($project->name); ?> (<?php echo e($project->key); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">All Statuses</option>
                                <option value="todo" <?php echo e(request('status') == 'todo' ? 'selected' : ''); ?>>To Do</option>
                                <option value="in_progress" <?php echo e(request('status') == 'in_progress' ? 'selected' : ''); ?>>In
                                    Progress</option>
                                <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed
                                </option>
                                <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>
                                    Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                            <select name="priority" id="priority"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">All Priorities</option>
                                <option value="low" <?php echo e(request('priority') == 'low' ? 'selected' : ''); ?>>Low</option>
                                <option value="medium" <?php echo e(request('priority') == 'medium' ? 'selected' : ''); ?>>Medium
                                </option>
                                <option value="high" <?php echo e(request('priority') == 'high' ? 'selected' : ''); ?>>High</option>
                                <option value="critical" <?php echo e(request('priority') == 'critical' ? 'selected' : ''); ?>>Critical
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                            <select name="type" id="type"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">All Types</option>
                                <option value="story" <?php echo e(request('type') == 'story' ? 'selected' : ''); ?>>Story</option>
                                <option value="bug" <?php echo e(request('type') == 'bug' ? 'selected' : ''); ?>>Bug</option>
                                <option value="task" <?php echo e(request('type') == 'task' ? 'selected' : ''); ?>>Task</option>
                                <option value="epic" <?php echo e(request('type') == 'epic' ? 'selected' : ''); ?>>Epic</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="btn btn-primary w-full">
                                Filter
                            </button>
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
                                            Task
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Project
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Priority
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Assignee
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Due Date
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-6 w-6">
                                                        <?php if($task->type === 'story'): ?>
                                                            <div
                                                                 class="h-6 w-6 bg-green-500 rounded flex items-center justify-center">
                                                                <svg class="h-4 w-4 text-white" fill="currentColor"
                                                                     viewBox="0 0 20 20">
                                                                    <path
                                                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            </div>
                                                        <?php elseif($task->type === 'bug'): ?>
                                                            <div
                                                                 class="h-6 w-6 bg-red-500 rounded flex items-center justify-center">
                                                                <svg class="h-4 w-4 text-white" fill="currentColor"
                                                                     viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                          clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                        <?php elseif($task->type === 'epic'): ?>
                                                            <div
                                                                 class="h-6 w-6 bg-purple-500 rounded flex items-center justify-center">
                                                                <svg class="h-4 w-4 text-white" fill="currentColor"
                                                                     viewBox="0 0 20 20">
                                                                    <path
                                                                          d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                                                                </svg>
                                                            </div>
                                                        <?php else: ?>
                                                            <div
                                                                 class="h-6 w-6 bg-blue-500 rounded flex items-center justify-center">
                                                                <svg class="h-4 w-4 text-white" fill="currentColor"
                                                                     viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                          d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                                          clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <a href="<?php echo e(route('tasks.show', $task)); ?>"
                                                               class="hover:text-blue-600">
                                                                <?php echo e($task->title); ?>

                                                            </a>
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            <?php echo e(ucfirst($task->type)); ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 rounded-full mr-2"
                                                         style="background-color: <?php echo e($task->project->color); ?>"></div>
                                                    <div class="text-sm text-gray-900"><?php echo e($task->project->name); ?></div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    <?php if($task->status === 'completed'): ?> bg-green-100 text-green-800
                                                    <?php elseif($task->status === 'in_progress'): ?> bg-blue-100 text-blue-800
                                                    <?php elseif($task->status === 'todo'): ?> bg-gray-100 text-gray-800
                                                    <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
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
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php if($task->assignee): ?>
                                                    <div class="flex items-center">
                                                        <div
                                                             class="h-6 w-6 bg-gray-300 rounded-full flex items-center justify-center">
                                                            <span class="text-xs font-medium text-gray-700">
                                                                <?php echo e($task->assignee->getInitials()); ?>

                                                            </span>
                                                        </div>
                                                        <div class="ml-2 text-sm text-gray-900">
                                                            <?php echo e($task->assignee->name); ?>

                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-sm text-gray-500">Unassigned</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php if($task->due_date): ?>
                                                    <span
                                                          class="<?php echo e($task->due_date->isPast() && !in_array($task->status, ['completed', 'cancelled']) ? 'text-red-600 font-medium' : ''); ?>">
                                                        <?php echo e($task->due_date->format('M j, Y')); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400">No due date</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="<?php echo e(route('tasks.show', $task)); ?>"
                                                       class="text-blue-600 hover:text-blue-900">
                                                        View
                                                    </a>
                                                    <a href="<?php echo e(route('tasks.edit', $task)); ?>"
                                                       class="text-indigo-600 hover:text-indigo-900">
                                                        Edit
                                                    </a>
                                                    <?php if(
                                                        $task->creator_id === auth()->id() ||
                                                            $task->assignee_id === auth()->id() ||
                                                            $task->project->owner_id === auth()->id() ||
                                                            auth()->user()->isAdmin()): ?>
                                                        <form method="POST" action="<?php echo e(route('tasks.destroy', $task)); ?>"
                                                              class="inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this task?')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit"
                                                                    class="text-red-600 hover:text-red-900">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
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
                            <p class="mt-1 text-sm text-gray-500">
                                <?php if(request()->hasAny(['search', 'status', 'priority', 'type', 'project'])): ?>
                                    Try adjusting your filters or search terms.
                                <?php else: ?>
                                    Get started by creating your first task.
                                <?php endif; ?>
                            </p>
                            <div class="mt-6">
                                <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary">
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\d\HomeProjects\tasksIveto\resources\views/tasks/global-index.blade.php ENDPATH**/ ?>