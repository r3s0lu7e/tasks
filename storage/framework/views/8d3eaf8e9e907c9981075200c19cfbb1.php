

<?php $__env->startSection('content'); ?>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-4 h-4 rounded" style="background-color: <?php echo e($project->color); ?>"></div>
                            <div>
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                    Project Members: <?php echo e($project->name); ?>

                                </h2>
                                <p class="text-sm text-gray-600"><?php echo e($project->key); ?></p>
                            </div>
                        </div>
                        <a href="<?php echo e(route('projects.show', $project)); ?>"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Project
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Add Member Form -->
                    <?php if($project->owner_id === auth()->id() || auth()->user()->isAdmin()): ?>
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Member</h3>
                            <form method="POST" action="<?php echo e(route('projects.add-member', $project)); ?>"
                                  class="flex items-end space-x-4">
                                <?php echo csrf_field(); ?>
                                <div class="flex-1">
                                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Select User
                                    </label>
                                    <select id="user_id" name="user_id" required
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Choose a user...</option>
                                        <?php $__currentLoopData = $availableUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Add Member
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <!-- Current Members -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Current Members
                            (<?php echo e($project->members->count() + 1); ?>)</h3>

                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <ul class="divide-y divide-gray-200">
                                <!-- Project Owner -->
                                <li class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                     class="h-10 w-10 rounded-full bg-jira-blue flex items-center justify-center">
                                                    <span class="text-sm font-medium text-white">
                                                        <?php echo e(strtoupper(substr($project->owner->name, 0, 2))); ?>

                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo e($project->owner->name); ?></div>
                                                    <span
                                                          class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Owner
                                                    </span>
                                                </div>
                                                <div class="text-sm text-gray-500"><?php echo e($project->owner->email); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo e(ucfirst($project->owner->role)); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Cannot be removed
                                        </div>
                                    </div>
                                </li>

                                <!-- Project Members -->
                                <?php $__currentLoopData = $project->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="px-6 py-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div
                                                         class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">
                                                            <?php echo e(strtoupper(substr($member->name, 0, 2))); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900"><?php echo e($member->name); ?>

                                                        </div>
                                                        <span
                                                              class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Member
                                                        </span>
                                                    </div>
                                                    <div class="text-sm text-gray-500"><?php echo e($member->email); ?></div>
                                                    <div class="text-sm text-gray-500"><?php echo e(ucfirst($member->role)); ?></div>
                                                </div>
                                            </div>

                                            <?php if($project->owner_id === auth()->id() || auth()->user()->isAdmin()): ?>
                                                <div class="flex items-center space-x-2">
                                                    <form method="POST"
                                                          action="<?php echo e(route('projects.remove-member', [$project, $member])); ?>"
                                                          class="inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit"
                                                                onclick="return confirm('Are you sure you want to remove <?php echo e($member->name); ?> from this project?')"
                                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150">
                                                            Remove
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if($project->members->isEmpty()): ?>
                                    <li class="px-6 py-4">
                                        <div class="text-center text-gray-500">
                                            No additional members yet. Add members to collaborate on this project.
                                        </div>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Member Statistics -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600"><?php echo e($project->members->count() + 1); ?></div>
                            <div class="text-sm text-blue-600">Total Members</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">
                                <?php echo e($project->members->where('role', 'developer')->count()); ?></div>
                            <div class="text-sm text-green-600">Developers</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">
                                <?php echo e($project->members->where('role', 'tester')->count()); ?></div>
                            <div class="text-sm text-purple-600">Testers</div>
                        </div>
                    </div>

                    <!-- Member Roles Info -->
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Member Roles</h4>
                        <div class="text-sm text-gray-600 space-y-1">
                            <div><strong>Admin:</strong> Full system access, can manage all projects</div>
                            <div><strong>Project Manager:</strong> Can create and manage projects, assign tasks</div>
                            <div><strong>Developer:</strong> Can work on assigned tasks, create subtasks</div>
                            <div><strong>Tester:</strong> Can test tasks, report bugs, update task status</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\d\HomeProjects\tasksIveto\resources\views/projects/members.blade.php ENDPATH**/ ?>