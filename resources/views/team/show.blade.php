@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-16 w-16 rounded-full flex items-center justify-center"
                                 style="background-color: rgba({{ $team->status_color_rgb }}, 0.2)">
                                <span class="text-2xl font-medium" style="color: {{ $team->status_color }}">
                                    {{ $team->getInitials() }}
                                </span>
                            </div>
                            <div class="ml-6">
                                <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
                                    {{ $team->name }}
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400">{{ $team->email }}</p>
                                <div class="flex items-center space-x-4 mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          style="background-color: rgba({{ $team->status_color_rgb }}, 0.2); color: {{ $team->status_color }}">
                                        {{ ucfirst($team->status) }}
                                    </span>
                                    <span
                                          class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $team->role)) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('team.edit', $team) }}" class="btn btn-primary">
                                Edit Member
                            </a>
                            <a href="{{ route('team.index') }}" class="btn btn-secondary">
                                Back to Team
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Performance Metrics -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="font-semibold text-lg text-gray-800 dark:text-white">Performance Metrics</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Tasks</span>
                                    <span
                                          class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTasks }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Completed</span>
                                    <span
                                          class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $completedTasks }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Current
                                        Workload</span>
                                    <span
                                          class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $currentWorkload }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Overdue Tasks</span>
                                    <span
                                          class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $overdueTasks }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Completion
                                        Rate</span>
                                    <span
                                          class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $completionRate }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Info -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="font-semibold text-lg text-gray-800 dark:text-white">Personal Information</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @if ($team->department)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $team->department }}</dd>
                                    </div>
                                @endif
                                @if ($team->phone)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $team->phone }}</dd>
                                    </div>
                                @endif
                                @if ($team->hourly_rate)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hourly Rate</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-200">${{ $team->hourly_rate }}</dd>
                                    </div>
                                @endif
                                @if ($team->hire_date)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hire Date</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-200">
                                            {{ $team->hire_date->format('d.m.Y') }}</dd>
                                    </div>
                                @endif
                                @if ($team->notes)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $team->notes }}</dd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="font-semibold text-lg text-gray-800 dark:text-white">Assigned Tasks</h3>
                        </div>
                        <div class="p-6">
                            @if ($team->assignedTasks->count() > 0)
                                <div class="space-y-4">
                                    @foreach ($team->assignedTasks as $task)
                                        <div
                                             class="flex items-start justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-full text-xs font-medium dark:text-gray-200"
                                                     style="background-color: rgba({{ $task->type->rgb_color }}, 0.2); color: {{ $task->type->color }}">
                                                    <i class="fas {{ $task->type->icon }}"></i>
                                                </div>
                                                <div>
                                                    <a href="{{ route('tasks.show', $task) }}"
                                                       class="text-sm font-medium text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ $task->title }}
                                                    </a>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        #{{ $task->id }} •
                                                        <a href="{{ route('projects.show', $task->project) }}"
                                                           class="hover:underline">{{ $task->project->name }}</a>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right flex-shrink-0">
                                                <div class="flex items-center space-x-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium dark:text-gray-200"
                                                          style="background-color: rgba({{ $task->status->rgb_color }}, 0.2); color: {{ $task->status->color }}">
                                                        {{ $task->status->name }}
                                                    </span>
                                                    <span
                                                          class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ config('colors.task_priority')[$task->priority] ?? '' }}">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </div>
                                                @if ($task->due_date)
                                                    <p
                                                       class="mt-1 text-xs {{ $task->due_date->isPast() && !in_array($task->status->alias, ['completed', 'cancelled']) ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}">
                                                        Due: {{ $task->due_date->format('d.m.Y') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div
                                     class="text-center py-12 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-inner col-span-1 lg:col-span-2">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No tasks assigned
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This team member has no
                                        assigned tasks yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
