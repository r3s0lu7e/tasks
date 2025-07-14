@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                            All Tasks
                        </h2>
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                            Create New Task
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <form method="GET" action="{{ route('tasks.index') }}" id="filter-form"
                          class="grid grid-cols-1 md:grid-cols-9 gap-4">
                        <div>
                            <label for="search"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Search tasks..."
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="project"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">Project</label>
                            <select name="project" id="project"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">All Projects</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                            {{ request('project') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" id="status"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}"
                                            {{ request('status') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="priority"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priority</label>
                            <select name="priority" id="priority"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">All Priorities</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium
                                </option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>
                                    Critical</option>
                            </select>
                        </div>

                        <div>
                            <label for="type"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                            <select name="type" id="type"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">All Types</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}"
                                            {{ request('type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="assignee"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assignee</label>
                            <select name="assignee" id="assignee"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">All Assignees</option>
                                <option value="unassigned" {{ request('assignee') === 'unassigned' ? 'selected' : '' }}>
                                    Unassigned</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                            {{ request('assignee') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="due_date_range"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                            <select name="due_date_range" id="due_date_range"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Any Time</option>
                                <option value="today" {{ request('due_date_range') == 'today' ? 'selected' : '' }}>Today
                                </option>
                                <option value="this_week" {{ request('due_date_range') == 'this_week' ? 'selected' : '' }}>
                                    This Week</option>
                                <option value="next_7_days"
                                        {{ request('due_date_range') == 'next_7_days' ? 'selected' : '' }}>Next 7 Days
                                </option>
                                <option value="this_month"
                                        {{ request('due_date_range') == 'this_month' ? 'selected' : '' }}>This Month
                                </option>
                                <option value="overdue" {{ request('due_date_range') == 'overdue' ? 'selected' : '' }}>
                                    Overdue</option>
                                <option value="no_due_date"
                                        {{ request('due_date_range') == 'no_due_date' ? 'selected' : '' }}>No Due Date
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="sort_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sort
                                By</label>
                            <select name="sort_by" id="sort_by"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="created_at_desc"
                                        {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>Newest First
                                </option>
                                <option value="created_at_asc"
                                        {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>Oldest First
                                </option>
                                <option value="due_date_asc" {{ request('sort_by') == 'due_date_asc' ? 'selected' : '' }}>
                                    Due Date Ascending</option>
                                <option value="due_date_desc"
                                        {{ request('sort_by') == 'due_date_desc' ? 'selected' : '' }}>Due Date Descending
                                </option>
                                <option value="priority_desc"
                                        {{ request('sort_by') == 'priority_desc' ? 'selected' : '' }}>Priority (High to
                                    Low)</option>
                                <option value="priority_asc" {{ request('sort_by') == 'priority_asc' ? 'selected' : '' }}>
                                    Priority (Low to High)</option>
                                <option value="updated_at_desc"
                                        {{ request('sort_by') == 'updated_at_desc' ? 'selected' : '' }}>Last Updated
                                </option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="btn btn-primary w-full">
                                Filter
                            </button>
                        </div>
                        <div class="flex items-end">
                            <button type="button" id="save-filter-btn" class="btn btn-secondary w-full">
                                Save Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Saved Filters -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Saved Filters</h3>
                <div id="saved-filters-list" class="flex flex-wrap gap-2">
                    @foreach (auth()->user()->savedFilters as $savedFilter)
                        <div
                             class="flex items-center px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full text-sm">
                            <a href="{{ route('tasks.index', $savedFilter->filters) }}"
                               class="hover:bg-gray-300 dark:hover:bg-gray-600">
                                {{ $savedFilter->name }}
                            </a>
                            <button data-filter-id="{{ $savedFilter->id }}"
                                    class="ml-2 text-red-500 hover:text-red-700 delete-filter-btn">
                                &times;
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tasks Table -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    @if ($tasks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">
                                            Task
                                        </th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">
                                            Project
                                        </th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">
                                            Status
                                        </th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                                            Priority
                                        </th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                            Assignee
                                        </th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">
                                            Due Date
                                        </th>
                                        <th
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($tasks as $task)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-full text-xs font-medium dark:text-gray-200"
                                                         style="background-color: rgba({{ $task->type->rgb_color }}, 0.2); color: {{ $task->type->color }}">
                                                        <i class="fas {{ $task->type->icon }}"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div
                                                             class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                            <a href="{{ route('tasks.show', $task) }}"
                                                               class="hover:text-jira-blue dark:hover:text-blue-400"
                                                               title="{{ $task->title }}">
                                                                {{ $task->title }}
                                                            </a>
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $task->project->key }}-{{ $task->id }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-3 py-4">
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 rounded-full mr-2"
                                                         style="background-color: {{ $task->project->color }}"></div>
                                                    <div class="text-sm text-gray-900 dark:text-white truncate">
                                                        <span
                                                              title="{{ $task->project->name }}">{{ $task->project->name }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-3 py-4">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium dark:text-gray-200"
                                                      style="background-color: rgba({{ $task->status->rgb_color }}, 0.2); color: {{ $task->status->color }}">
                                                    {{ $task->status->name }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-4">
                                                <span
                                                      class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ config('colors.task_priority')[$task->priority] ?? '' }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-4 text-sm text-gray-900 dark:text-white truncate">
                                                <span
                                                      title="{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}">
                                                    {{ $task->assignee ? $task->assignee->name : 'Unassigned' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-4 text-sm text-gray-900 dark:text-white">
                                                <span class="whitespace-nowrap">
                                                    {{ $task->due_date ? $task->due_date->format('d.m.Y') : 'No due date' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-4 text-sm font-medium">
                                                <div class="flex items-center space-x-3">
                                                    <a href="{{ route('tasks.show', $task) }}"
                                                       class="text-jira-blue dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 p-1 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20"
                                                       title="View Task">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                             viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('tasks.edit', $task) }}"
                                                       class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 p-1 rounded hover:bg-gray-50 dark:hover:bg-gray-700"
                                                       title="Edit Task">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                             viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    @if ($task->creator_id === auth()->id() || auth()->user()->isAdmin())
                                                        <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                                              class="inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this task?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 p-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20"
                                                                    title="Delete Task">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                     viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                          stroke-width="2"
                                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $tasks->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No tasks found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new task.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                                    Create Task
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
