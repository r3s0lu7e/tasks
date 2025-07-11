

<?php $__env->startSection('content'); ?>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Task Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div
                                 class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full text-sm font-medium
                            <?php if($task->type === 'story'): ?> bg-green-100 text-green-800
                            <?php elseif($task->type === 'bug'): ?> bg-red-100 text-red-800
                            <?php elseif($task->type === 'epic'): ?> bg-purple-100 text-purple-800
                            <?php else: ?> bg-blue-100 text-blue-800 <?php endif; ?>">
                                <?php echo e(strtoupper(substr($task->type, 0, 1))); ?>

                            </div>
                            <div>
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                    <?php echo e($task->title); ?>

                                </h2>
                                <p class="text-sm text-gray-600"><?php echo e($project->key); ?>-<?php echo e($task->id); ?></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="<?php echo e(route('projects.tasks.edit', [$project, $task])); ?>"
                               class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Edit Task
                            </a>
                            <a href="<?php echo e(route('projects.tasks.index', $project)); ?>"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Back to Tasks
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Task Details -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Task Details</h3>

                            <?php if($task->description): ?>
                                <div class="mb-6">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                                    <div class="text-gray-700 whitespace-pre-wrap"><?php echo e($task->description); ?></div>
                                </div>
                            <?php endif; ?>

                            <!-- Task Metadata -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Type</h4>
                                    <span
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php if($task->type === 'story'): ?> bg-green-100 text-green-800
                                    <?php elseif($task->type === 'bug'): ?> bg-red-100 text-red-800
                                    <?php elseif($task->type === 'epic'): ?> bg-purple-100 text-purple-800
                                    <?php else: ?> bg-blue-100 text-blue-800 <?php endif; ?>">
                                        <?php echo e(ucfirst($task->type)); ?>

                                    </span>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Priority</h4>
                                    <span
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php if($task->priority === 'critical'): ?> bg-red-100 text-red-800
                                    <?php elseif($task->priority === 'high'): ?> bg-orange-100 text-orange-800
                                    <?php elseif($task->priority === 'medium'): ?> bg-yellow-100 text-yellow-800
                                    <?php else: ?> bg-green-100 text-green-800 <?php endif; ?>">
                                        <?php echo e(ucfirst($task->priority)); ?>

                                    </span>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Status</h4>
                                    <span
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php if($task->status === 'completed'): ?> bg-green-100 text-green-800
                                    <?php elseif($task->status === 'in_progress'): ?> bg-blue-100 text-blue-800
                                    <?php elseif($task->status === 'cancelled'): ?> bg-red-100 text-red-800
                                    <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $task->status))); ?>

                                    </span>
                                </div>

                                <?php if($task->story_points): ?>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Story Points</h4>
                                        <span class="text-sm text-gray-900"><?php echo e($task->story_points); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Dates -->
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Created</h4>
                                    <p class="text-sm text-gray-900"><?php echo e($task->created_at->format('M j, Y \a\t g:i A')); ?>

                                    </p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Last Updated</h4>
                                    <p class="text-sm text-gray-900"><?php echo e($task->updated_at->format('M j, Y \a\t g:i A')); ?>

                                    </p>
                                </div>

                                <?php if($task->due_date): ?>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Due Date</h4>
                                        <p
                                           class="text-sm <?php if($task->due_date < now() && !in_array($task->status, ['completed', 'cancelled'])): ?> text-red-600 <?php else: ?> text-gray-900 <?php endif; ?>">
                                            <?php echo e($task->due_date->format('M j, Y')); ?>

                                            <?php if($task->due_date < now() && !in_array($task->status, ['completed', 'cancelled'])): ?>
                                                <span class="text-red-600 font-medium">(Overdue)</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Comments</h3>

                            <!-- Add Comment Form -->
                            <form method="POST" action="<?php echo e(route('tasks.comments.store', $task)); ?>" class="mb-6">
                                <?php echo csrf_field(); ?>
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-jira-blue flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 2))); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <textarea name="comment" rows="3" placeholder="Add a comment..."
                                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm"
                                                  required></textarea>
                                        <div class="mt-2">
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Add Comment
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Comments List -->
                            <div class="space-y-4">
                                <?php $__empty_1 = true; $__currentLoopData = $task->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    <?php echo e(strtoupper(substr($comment->user->name, 0, 2))); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <span
                                                      class="text-sm font-medium text-gray-900"><?php echo e($comment->user->name); ?></span>
                                                <span
                                                      class="text-xs text-gray-500"><?php echo e($comment->created_at->diffForHumans()); ?></span>
                                            </div>
                                            <div class="mt-1 text-sm text-gray-700 whitespace-pre-wrap">
                                                <?php echo e($comment->comment); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-gray-500 text-sm">No comments yet. Be the first to comment!</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attachments -->
                <?php if($task->attachments->count() > 0): ?>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Attachments
                                (<?php echo e($task->attachments->count()); ?>)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php $__currentLoopData = $task->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="border border-gray-200 rounded-lg p-4 flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <?php if($attachment->icon === 'image'): ?>
                                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            <?php elseif($attachment->icon === 'pdf'): ?>
                                                <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            <?php elseif($attachment->icon === 'document'): ?>
                                                <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            <?php elseif($attachment->icon === 'archive'): ?>
                                                <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                    </path>
                                                </svg>
                                            <?php else: ?>
                                                <svg class="h-8 w-8 text-gray-500" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                <?php echo e($attachment->filename); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo e($attachment->file_size); ?> •
                                                <?php echo e($attachment->created_at->format('M j, Y')); ?></p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="<?php echo e(route('tasks.attachments.download', [$task, $attachment])); ?>"
                                               class="text-blue-600 hover:text-blue-800 text-sm">
                                                Download
                                            </a>
                                            <form method="POST"
                                                  action="<?php echo e(route('tasks.attachments.delete', [$task, $attachment])); ?>"
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this attachment?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="lg:col-span-1">
                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>

                            <!-- Status Update -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                                <select id="status-update"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm">
                                    <option value="todo" <?php echo e($task->status === 'todo' ? 'selected' : ''); ?>>To Do</option>
                                    <option value="in_progress" <?php echo e($task->status === 'in_progress' ? 'selected' : ''); ?>>In
                                        Progress</option>
                                    <option value="completed" <?php echo e($task->status === 'completed' ? 'selected' : ''); ?>>
                                        Completed</option>
                                    <option value="cancelled" <?php echo e($task->status === 'cancelled' ? 'selected' : ''); ?>>
                                        Cancelled</option>
                                </select>
                            </div>

                            <!-- Assignee Update -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                                <select id="assignee-update"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm">
                                    <option value="">Unassigned</option>
                                    <?php $__currentLoopData = $assignableUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"
                                                <?php echo e($task->assignee_id == $user->id ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Task Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Task Information</h3>

                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Project</h4>
                                    <a href="<?php echo e(route('projects.show', $project)); ?>"
                                       class="text-sm text-jira-blue hover:text-blue-700">
                                        <?php echo e($project->name); ?>

                                    </a>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Creator</h4>
                                    <p class="text-sm text-gray-900"><?php echo e($task->creator->name); ?></p>
                                </div>

                                <?php if($task->assignee): ?>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700">Assignee</h4>
                                        <div class="flex items-center space-x-2">
                                            <div class="h-6 w-6 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-700">
                                                    <?php echo e(strtoupper(substr($task->assignee->name, 0, 2))); ?>

                                                </span>
                                            </div>
                                            <span class="text-sm text-gray-900"><?php echo e($task->assignee->name); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Task -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Danger Zone</h3>
                            <button type="button" onclick="confirmDelete()"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Delete Task
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="delete-form" method="POST" action="<?php echo e(route('projects.tasks.destroy', [$project, $task])); ?>"
          class="hidden">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>

    <script>
        // Status update
        document.getElementById('status-update').addEventListener('change', function() {
            const status = this.value;
            fetch(`/tasks/<?php echo e($task->id); ?>/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating status');
                    }
                });
        });

        // Assignee update
        document.getElementById('assignee-update').addEventListener('change', function() {
            const assigneeId = this.value;
            fetch(`/tasks/<?php echo e($task->id); ?>/assign`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        assignee_id: assigneeId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating assignee');
                    }
                });
        });

        // Delete confirmation
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this task? This action cannot be undone.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\d\HomeProjects\tasksIveto\resources\views/tasks/show.blade.php ENDPATH**/ ?>