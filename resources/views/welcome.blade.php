<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ getWorkstationName() }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Prevent dark mode flash -->
    <script>
        // Initialize theme immediately to prevent flash
        (function() {
            try {
                if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                        '(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark')
                } else {
                    document.documentElement.classList.remove('dark')
                }
            } catch (e) {
                // Fallback to light mode if localStorage is not available
                document.documentElement.classList.remove('dark')
            }
        })()
    </script>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-jira-blue dark:text-blue-400 mb-2">
                    {{ getWorkstationName() }}</h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">{{ getWorkstationDescription() }}</p>
            </div>

            <div
                 class="bg-white dark:bg-gray-800 py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-200 dark:border-gray-700">
                <div class="space-y-6">
                    <div class="text-center">
                        <svg class="mx-auto h-16 w-16 text-jira-blue dark:text-blue-400" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Welcome to
                            {{ getWorkstationName() }}</h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            {{ getWorkstationDescription() }}
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">✅ Projects Management</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">✅ Task Assignment</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">✅ Team Collaboration</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">✅ Progress Tracking</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">✅ User Roles</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">✅ Modern UI</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-jira-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jira-blue dark:focus:ring-offset-gray-800">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-jira-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jira-blue dark:focus:ring-offset-gray-800">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}"
                               class="w-full flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jira-blue dark:focus:ring-offset-gray-800">
                                Create Account
                            </a>
                        @endauth
                    </div>


                </div>
            </div>
        </div>
    </div>
</body>

</html>
