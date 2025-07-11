<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Jira Clone')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="<?php echo e(route('dashboard')); ?>" class="text-xl font-bold text-jira-blue">
                                <?php echo e(config('app.name', 'Jira Clone')); ?>

                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <?php if(auth()->guard()->check()): ?>
                                <a href="<?php echo e(route('dashboard')); ?>"
                                   class="inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('dashboard') ? 'border-jira-blue text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> text-sm font-medium">
                                    Dashboard
                                </a>
                                <a href="<?php echo e(route('projects.index')); ?>"
                                   class="inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('projects.*') ? 'border-jira-blue text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> text-sm font-medium">
                                    Projects
                                </a>
                                <a href="<?php echo e(route('tasks.index')); ?>"
                                   class="inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('tasks.*') ? 'border-jira-blue text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> text-sm font-medium">
                                    Tasks
                                </a>
                                <a href="<?php echo e(route('team.index')); ?>"
                                   class="inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('team.*') ? 'border-jira-blue text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> text-sm font-medium">
                                    Team
                                </a>

                                <!-- Search -->
                                <div class="flex items-center">
                                    <form method="GET" action="<?php echo e(route('search.index')); ?>" class="flex items-center">
                                        <input type="text" name="q" placeholder="Search..."
                                               class="w-64 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <button type="submit" class="ml-2 p-2 text-gray-400 hover:text-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <?php if(auth()->guard()->check()): ?>
                            <div class="ml-3 relative" x-data="{ open: false }">
                                <div>
                                    <button @click="open = !open"
                                            class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jira-blue">
                                        <span class="sr-only">Open user menu</span>
                                        <div class="h-8 w-8 rounded-full bg-jira-blue flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">
                                                <?php echo e(substr(auth()->user()->name, 0, 1)); ?>

                                            </span>
                                        </div>
                                    </button>
                                </div>

                                <div x-show="open" @click.away="open = false"
                                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                    <div class="py-1">
                                        <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                            <div class="font-medium"><?php echo e(auth()->user()->name); ?></div>
                                            <div class="text-gray-500"><?php echo e(auth()->user()->email); ?></div>
                                        </div>
                                        <a href="<?php echo e(route('profile.show')); ?>"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                        <a href="<?php echo e(route('profile.edit')); ?>"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Sign out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="flex space-x-4">
                                <a href="<?php echo e(route('login')); ?>"
                                   class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                    Sign in
                                </a>
                                <a href="<?php echo e(route('register')); ?>"
                                   class="bg-jira-blue text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                    Sign up
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <?php if(session('success')): ?>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-4">
                    <div class="mb-4 p-4 rounded-md bg-green-50 border border-green-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                          clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-800"><?php echo e(session('success')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-4">
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                          clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-800"><?php echo e(session('error')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</body>

</html>
<?php /**PATH D:\d\HomeProjects\tasksIveto\resources\views/layouts/app.blade.php ENDPATH**/ ?>