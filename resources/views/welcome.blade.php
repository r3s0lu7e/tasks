<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Jira Clone') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-jira-blue mb-2">{{ config('app.name', 'Jira Clone') }}</h1>
                <p class="text-lg text-gray-600 mb-8">Modern Project Management System</p>
            </div>

            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <div class="space-y-6">
                    <div class="text-center">
                        <svg class="mx-auto h-16 w-16 text-jira-blue" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <h2 class="mt-4 text-xl font-semibold text-gray-900">Welcome to Jira Clone</h2>
                        <p class="mt-2 text-sm text-gray-600">
                            A comprehensive project management system built with Laravel 12
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">✅ Projects Management</span>
                            <span class="text-sm text-gray-500">✅ Task Assignment</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">✅ Team Collaboration</span>
                            <span class="text-sm text-gray-500">✅ Progress Tracking</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">✅ User Roles</span>
                            <span class="text-sm text-gray-500">✅ Modern UI</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-jira-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jira-blue">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-jira-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jira-blue">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}"
                               class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jira-blue">
                                Create Account
                            </a>
                        @endauth
                    </div>

                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <div class="text-center">
                            <h3 class="text-sm font-medium text-gray-900 mb-4">Sample Login Credentials</h3>
                            <div class="text-xs text-gray-600 space-y-2">
                                <div class="bg-gray-50 p-3 rounded">
                                    <strong>Admin:</strong> admin@jiraclone.com / password
                                </div>
                                <div class="bg-gray-50 p-3 rounded">
                                    <strong>Project Manager:</strong> pm@jiraclone.com / password
                                </div>
                                <div class="bg-gray-50 p-3 rounded">
                                    <strong>Developer:</strong> john@jiraclone.com / password
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
