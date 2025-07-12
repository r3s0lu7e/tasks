@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Header -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="h-16 w-16 rounded-full bg-jira-blue flex items-center justify-center">
                                <span class="text-xl font-medium text-white">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                                    {{ $user->name }}
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($user->role) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                           class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Statistics -->
                <div class="lg:col-span-2">
                    <div
                         class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Statistics</h3>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-200">
                                        {{ $stats['owned_projects'] }}</div>
                                    <div class="text-sm text-blue-600 dark:text-blue-200">Owned Projects</div>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-200">
                                        {{ $stats['member_projects'] }}</div>
                                    <div class="text-sm text-green-600 dark:text-green-200">Member Projects</div>
                                </div>
                                <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-200">
                                        {{ $stats['assigned_tasks'] }}</div>
                                    <div class="text-sm text-purple-600 dark:text-purple-200">Assigned Tasks</div>
                                </div>
                                <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-200">
                                        {{ $stats['completed_tasks'] }}</div>
                                    <div class="text-sm text-yellow-600 dark:text-yellow-200">Completed Tasks</div>
                                </div>
                                <div class="bg-indigo-50 dark:bg-indigo-900 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-200">
                                        {{ $stats['created_tasks'] }}</div>
                                    <div class="text-sm text-indigo-600 dark:text-indigo-200">Created Tasks</div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-600 dark:text-gray-200">
                                        {{ $stats['assigned_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['assigned_tasks']) * 100, 1) : 0 }}%
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-200">Completion Rate</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Tasks -->
                    <div
                         class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Tasks</h3>

                            @if ($recentTasks->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($recentTasks as $task)
                                        <div
                                             class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                     class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-full text-xs font-medium
                                                @if ($task->type === 'story') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                                @elseif($task->type === 'bug') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                                @elseif($task->type === 'epic') bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200
                                                @else bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 @endif">
                                                    {{ strtoupper(substr($task->type, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}"
                                                           class="hover:text-jira-blue dark:hover:text-blue-400">
                                                            {{ $task->title }}
                                                        </a>
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $task->project->name }} •
                                                        {{ $task->project->key }}-{{ $task->id }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span
                                                      class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if ($task->status === 'completed') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                                @elseif($task->status === 'in_progress') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                                @elseif($task->status === 'cancelled') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $task->updated_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm">No recent tasks found.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="space-y-6">
                    <div
                         class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Profile Information</h3>

                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</h4>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $user->name }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</h4>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $user->email }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Role</h4>
                                    <span
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($user->role === 'admin') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                    @elseif($user->role === 'project_manager') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                    @elseif($user->role === 'developer') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                    @else bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Member Since</h4>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $user->created_at->format('F j, Y') }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Last Login</h4>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $user->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div
                         class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>

                            <div class="space-y-3">
                                <a href="{{ route('projects.index') }}"
                                   class="w-full inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    View My Projects
                                </a>

                                <a href="{{ route('dashboard') }}"
                                   class="w-full inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Go to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
