@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Header -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}!</h1>
                            <p class="text-blue-100 mt-1">Manage your team and track project progress</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('projects.create') }}"
                               class="bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                New Project
                            </a>
                            <a href="{{ route('tasks.create') }}"
                               class="bg-white dark:bg-gray-800 text-purple-600 dark:text-purple-400 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                New Task
                            </a>
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('team.create') }}"
                                   class="bg-white dark:bg-gray-800 text-green-600 dark:text-green-400 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                        </path>
                                    </svg>
                                    Add Team Member
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border-l-4 border-blue-500">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Projects</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $stats['total_projects'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border-l-4 border-purple-500">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Tasks
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $stats['total_tasks'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border-l-4 border-green-500">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Completed</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $stats['completed_tasks'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border-l-4 border-red-500">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Overdue</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $stats['overdue_tasks'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border-l-4 border-yellow-500">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Due Today
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $stats['today_tasks'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-indigo-500">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Team Members</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['team_members'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div> --}}

                @if (auth()->user()->isAdmin())
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border-l-4 border-teal-500">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-teal-500" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active
                                            Team</dt>
                                        <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                            {{ $stats['active_team_members'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Due Today Tasks Alert -->
            @if ($todayTasks->count() > 0)
                <div
                     class="mb-4 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-800 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                      clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                📅 {{ $todayTasks->count() }} {{ Str::plural('task', $todayTasks->count()) }}
                                due today
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($todayTasks->take(3) as $task)
                                        <li>
                                            <a href="{{ route('tasks.show', $task) }}" class="hover:underline">
                                                {{ $task->title }}
                                            </a>
                                            @if ($task->assignee)
                                                ({{ $task->assignee->name }})
                                            @endif
                                            for {{ $task->project->name }}
                                        </li>
                                    @endforeach
                                    @if ($todayTasks->count() > 3)
                                        <li>And {{ $todayTasks->count() - 3 }} more...</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Overdue Tasks Alert -->
            @if ($overdueTasks->count() > 0)
                <div class="mb-8 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-800 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400 dark:text-red-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                      clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                ⚠️ {{ $overdueTasks->count() }} overdue {{ Str::plural('task', $overdueTasks->count()) }}
                                need attention
                            </h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($overdueTasks->take(3) as $task)
                                        <li>
                                            <a href="{{ route('tasks.show', $task) }}" class="hover:underline">
                                                {{ $task->title }}
                                            </a>
                                            @if ($task->assignee)
                                                ({{ $task->assignee->name }})
                                            @endif
                                            - Due: {{ $task->due_date->format('M j, Y') }}
                                        </li>
                                    @endforeach
                                    @if ($overdueTasks->count() > 3)
                                        <li>And {{ $overdueTasks->count() - 3 }} more...</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-{{ auth()->user()->isAdmin() ? '3' : '2' }} gap-8">
                <!-- Team Members Overview - Admin Only -->
                @if (auth()->user()->isAdmin())
                    <div
                         class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Team Members</h3>
                                <a href="{{ route('team.index') }}"
                                   class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View
                                    all</a>
                            </div>
                            <div class="space-y-3">
                                @forelse($teamMembers as $member)
                                    <div
                                         class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <div class="flex items-center">
                                            <div
                                                 class="h-8 w-8 rounded-full bg-{{ $member->status_color }}-500 flex items-center justify-center">
                                                <span
                                                      class="text-white text-sm font-medium">{{ $member->initials }}</span>
                                            </div>
                                            <div class="ml-3">
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                    <a href="{{ route('team.show', $member) }}"
                                                       class="hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ $member->name }}
                                                    </a>
                                                </h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $member->role }} •
                                                    {{ $member->position }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                  class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->assigned_tasks_count }}</span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">tasks</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                            </path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No team members
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add team members to start
                                            tracking their work.
                                        </p>
                                        <div class="mt-6">
                                            <a href="{{ route('team.create') }}" class="btn btn-primary">
                                                Add Team Member
                                            </a>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Active Projects -->
                <div
                     class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Active Projects</h3>
                            <a href="{{ route('projects.index') }}"
                               class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View
                                all</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($projects as $project)
                                <div
                                     class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3"
                                             style="background-color: {{ $project->color }}"></div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('projects.show', $project) }}"
                                                   class="hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $project->name }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $project->tasks->count() }} tasks •
                                                {{ $project->members->count() }} members</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span
                                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $project->status === 'active' ? 'green' : 'gray' }}-100 dark:bg-{{ $project->status === 'active' ? 'green' : 'gray' }}-900 text-{{ $project->status === 'active' ? 'green' : 'gray' }}-800 dark:text-{{ $project->status === 'active' ? 'green' : 'gray' }}-200">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </div>
                                        <div class="w-20 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                            <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full"
                                                 style="width: {{ $project->progress }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $project->progress }}%
                                            complete</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No projects yet</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create your first project to
                                        get started.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('projects.create') }}" class="btn btn-primary">
                                            Create Project
                                        </a>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div
                     class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Recent Activity</h3>
                            <a href="{{ route('tasks.index') }}"
                               class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View
                                all</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($recentActivity->take(6) as $task)
                                <div
                                     class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 rounded-full mr-3"
                                             style="background-color: {{ $task->project->color }}"></div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('tasks.show', $task) }}"
                                                   class="hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ Str::limit($task->title, 25) }}
                                                </a>
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $task->project->name }}
                                                @if ($task->assignee)
                                                    • {{ $task->assignee->name }}
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $task->updated_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end space-y-1">
                                        <span
                                              class="px-2 py-1 text-xs font-medium rounded-full
                                            @if ($task->status === 'todo') bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white
                                            @elseif($task->status === 'in_progress') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-white
                                            @elseif($task->status === 'completed') bg-green-100 dark:bg-green-900 text-green-800 dark:text-white
                                            @elseif($task->status === 'blocked') bg-red-100 dark:bg-red-900 text-red-800 dark:text-white
                                            @elseif($task->status === 'cancelled') bg-red-100 dark:bg-red-900 text-red-800 dark:text-white
                                            @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white @endif">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                        <span
                                              class="px-2 py-1 text-xs font-medium rounded-full
                                            @if ($task->priority === 'low') bg-green-100 dark:bg-green-900 text-green-800 dark:text-white
                                            @elseif($task->priority === 'medium') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-white
                                            @elseif($task->priority === 'high') bg-red-100 dark:bg-red-900 text-red-800 dark:text-white
                                            @elseif($task->priority === 'critical') bg-red-100 dark:bg-red-900 text-red-800 dark:text-white
                                            @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white @endif">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No recent activity
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Task activity will appear
                                        here.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Performance Overview - Admin Only -->
            @if (auth()->user()->isAdmin() && $teamWorkload->count() > 0)
                <div
                     class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Team Performance
                            Overview</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach ($teamWorkload as $member)
                                <div class="text-center">
                                    <div
                                         class="h-16 w-16 mx-auto rounded-full bg-{{ $member['status'] === 'active' ? 'green' : 'gray' }}-500 flex items-center justify-center mb-2">
                                        <span class="text-white font-medium">{{ substr($member['name'], 0, 2) }}</span>
                                    </div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $member['name'] }}
                                    </h4>
                                    <div class="mt-2 space-y-1">
                                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-300">
                                            <span>Workload:</span>
                                            <span class="font-medium">{{ $member['workload'] }} tasks</span>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-300">
                                            <span>Completion:</span>
                                            <span class="font-medium">{{ $member['completion_rate'] }}%</span>
                                        </div>
                                        @if ($member['overdue_count'] > 0)
                                            <div class="flex justify-between text-xs text-red-600 dark:text-red-400">
                                                <span>Overdue:</span>
                                                <span class="font-medium">{{ $member['overdue_count'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
