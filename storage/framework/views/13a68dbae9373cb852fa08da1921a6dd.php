

<?php $__env->startSection('content'); ?>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Projects
                        </h2>
                        <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-primary">
                            Create New Project
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white">
                    <?php if($projects->count() > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div
                                     class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 rounded"
                                                     style="background-color: <?php echo e($project->color); ?>"></div>
                                                <h3 class="ml-3 text-lg font-semibold text-gray-900">
                                                    <a href="<?php echo e(route('projects.show', $project)); ?>"
                                                       class="hover:text-blue-600">
                                                        <?php echo e($project->name); ?>

                                                    </a>
                                                </h3>
                                            </div>
                                            <span
                                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                <?php if($project->status === 'active'): ?> bg-green-100 text-green-800
                                                <?php elseif($project->status === 'planning'): ?> bg-blue-100 text-blue-800
                                                <?php elseif($project->status === 'on_hold'): ?> bg-yellow-100 text-yellow-800
                                                <?php elseif($project->status === 'completed'): ?> bg-gray-100 text-gray-800
                                                <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $project->status))); ?>

                                            </span>
                                        </div>

                                        <p class="text-sm text-gray-600 mb-4"><?php echo e($project->key); ?></p>

                                        <?php if($project->description): ?>
                                            <p class="text-sm text-gray-700 mb-4">
                                                <?php echo e(Str::limit($project->description, 100)); ?></p>
                                        <?php endif; ?>

                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <div class="flex items-center space-x-4">
                                                <span><?php echo e($project->total_tasks); ?> tasks</span>
                                                <span><?php echo e($project->members->count()); ?> members</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2 mr-2" style="width: 60px;">
                                                    <div class="bg-blue-600 h-2 rounded-full"
                                                         style="width: <?php echo e($project->progress); ?>%"></div>
                                                </div>
                                                <span class="text-xs"><?php echo e($project->progress); ?>%</span>
                                            </div>
                                        </div>

                                        <div class="mt-4 flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <span
                                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    <?php if($project->priority === 'critical'): ?> bg-red-100 text-red-800
                                                    <?php elseif($project->priority === 'high'): ?> bg-orange-100 text-orange-800
                                                    <?php elseif($project->priority === 'medium'): ?> bg-yellow-100 text-yellow-800
                                                    <?php else: ?> bg-green-100 text-green-800 <?php endif; ?>">
                                                    <?php echo e(ucfirst($project->priority)); ?>

                                                </span>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="<?php echo e(route('projects.show', $project)); ?>"
                                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    View
                                                </a>
                                                <a href="<?php echo e(route('projects.edit', $project)); ?>"
                                                   class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                                    Edit
                                                </a>
                                                <?php if($project->owner_id === auth()->id() || auth()->user()->isAdmin()): ?>
                                                    <form method="POST" action="<?php echo e(route('projects.destroy', $project)); ?>"
                                                          class="inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this project? This will also delete all associated tasks.')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit"
                                                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                            Delete
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No projects yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first project.</p>
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
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\d\HomeProjects\tasksIveto\resources\views/projects/index.blade.php ENDPATH**/ ?>