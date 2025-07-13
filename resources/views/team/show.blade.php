@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-16 w-16 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-2xl font-medium text-gray-700">
                                    {{ $team->getInitials() }}
                                </span>
                            </div>
                            <div class="ml-6">
                                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                                    {{ $team->name }}
                                </h2>
                                <p class="text-gray-600">{{ $team->email }}</p>
                                <div class="flex items-center space-x-4 mt-1">
                                    <span
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($team->status === 'active') bg-green-100 text-green-800
                                        @elseif($team->status === 'vacation') bg-yellow-100 text-yellow-800
                                        @elseif($team->status === 'busy') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($team->status) }}
                                    </span>
                                    <span
                                          class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $team->role)) }}</span>
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
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="font-semibold text-lg text-gray-800">Performance Metrics</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Total Tasks</span>
                                    <span class="text-2xl font-bold text-gray-900">{{ $totalTasks }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Completed</span>
                                    <span class="text-2xl font-bold text-green-600">{{ $completedTasks }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Current Workload</span>
                                    <span class="text-2xl font-bold text-blue-600">{{ $currentWorkload }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Overdue Tasks</span>
                                    <span class="text-2xl font-bold text-red-600">{{ $overdueTasks }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Completion Rate</span>
                                    <span class="text-2xl font-bold text-purple-600">{{ $completionRate }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="font-semibold text-lg text-gray-800">Personal Information</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @if ($team->department)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Department</dt>
                                        <dd class="text-sm text-gray-900">{{ $team->department }}</dd>
                                    </div>
                                @endif
                                @if ($team->phone)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                        <dd class="text-sm text-gray-900">{{ $team->phone }}</dd>
                                    </div>
                                @endif
                                @if ($team->hourly_rate)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Hourly Rate</dt>
                                        <dd class="text-sm text-gray-900">${{ $team->hourly_rate }}</dd>
                                    </div>
                                @endif
                                @if ($team->hire_date)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Hire Date</dt>
                                        <dd class="text-sm text-gray-900">{{ $team->hire_date->format('d.m.Y') }}
                                        </dd>
                                    </div>
                                @endif
                                @if ($team->notes)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                        <dd class="text-sm text-gray-900">{{ $team->notes }}</dd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="font-semibold text-lg text-gray-800">Assigned Tasks</h3>
                        </div>
                        <div class="p-6">
                            @if ($team->assignedTasks->count() > 0)
                                <div class="space-y-4">
                                    @foreach ($team->assignedTasks as $task)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-6 w-6">
                                                        @if ($task->type === 'story')
                                                            <div
                                                                 class="h-6 w-6 bg-green-500 rounded flex items-center justify-center">
                                                                <svg class="h-4 w-4 text-white" fill="currentColor"
                                                                     viewBox="0 0 20 20">
                                                                    <path
                                                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            </div>
                                                        @elseif($task->type === 'bug')
                                                            <div
                                                                 class="h-6 w-6 bg-red-500 rounded flex items-center justify-center">
                                                                <svg class="h-4 w-4 text-white" fill="currentColor"
                                                                     viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                          clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                        @elseif($task->type === 'epic')
                                                            <div
                                                                 class="h-6 w-6 bg-purple-500 rounded flex items-center justify-center">
                                                                <svg class="h-4 w-4 text-white" fill="currentColor"
                                                                     viewBox="0 0 20 20">
                                                                    <path
                                                                          d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                                                                </svg>
                                                            </div>
                                                        @else
                                                            <div
                                                                 class="h-6 w-6 bg-blue-500 rounded flex items-center justify-center">
                                                                <svg class="h-4 w-4 text-white" fill="currentColor"
                                                                     viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                          d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                                          clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <h4 class="text-sm font-medium text-gray-900">
                                                            <a href="{{ route('tasks.show', $task) }}"
                                                               class="hover:text-blue-600">
                                                                {{ $task->title }}
                                                            </a>
                                                        </h4>
                                                        <p class="text-sm text-gray-500">{{ $task->project->name }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
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
                                                    @if ($task->due_date)
                                                        <span
                                                              class="text-xs text-gray-500 {{ $task->due_date->isPast() && !in_array($task->status, ['completed', 'cancelled']) ? 'text-red-600 font-medium' : '' }}">
                                                            Due: {{ $task->due_date->format('d.m') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks assigned</h3>
                                    <p class="mt-1 text-sm text-gray-500">This team member has no assigned tasks yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
