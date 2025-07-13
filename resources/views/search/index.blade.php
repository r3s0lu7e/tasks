@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Header -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Search Results</h1>
                        <a href="{{ route('dashboard') }}"
                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">Back to
                            Dashboard</a>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('search.index') }}" class="mt-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <input type="text" name="q" value="{{ $query }}"
                                       placeholder="Search projects and tasks..."
                                       class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search
                            </button>
                        </div>
                    </form>

                    @if ($query)
                        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                            Found {{ $total }} results for "<strong>{{ $query }}</strong>"
                        </div>
                    @endif
                </div>
            </div>

            @if ($query && $total > 0)
                <div class="space-y-6">
                    <!-- Projects Section -->
                    @if ($projects->count() > 0)
                        <div
                             class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                    Projects ({{ $projects->count() }})
                                </h2>
                                <div class="space-y-4">
                                    @foreach ($projects as $project)
                                        <div
                                             class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-4 h-4 rounded"
                                                     style="background-color: {{ $project->color }}"></div>
                                                <div>
                                                    <h3 class="font-medium text-gray-900 dark:text-white">
                                                        <a href="{{ route('projects.show', $project) }}"
                                                           class="hover:text-blue-600 dark:hover:text-blue-400">
                                                            {{ $project->name }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $project->key }}
                                                    </p>
                                                    @if ($project->description)
                                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                                            {{ Str::limit($project->description, 100) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-4">
                                                <span
                                                      class="text-sm text-gray-500 dark:text-gray-400">{{ $project->tasks->count() }}
                                                    tasks</span>
                                                <span
                                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $project->status === 'active' ? 'green' : 'gray' }}-100 dark:bg-{{ $project->status === 'active' ? 'green' : 'gray' }}-900 text-{{ $project->status === 'active' ? 'green' : 'gray' }}-800 dark:text-{{ $project->status === 'active' ? 'green' : 'gray' }}-200">
                                                    {{ ucfirst($project->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Projects Pagination -->
                                <div class="mt-6">
                                    {{ $projects->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tasks Section -->
                    @if ($tasks->count() > 0)
                        <div
                             class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                    Tasks ({{ $tasks->count() }})
                                </h2>
                                <div class="space-y-3">
                                    @foreach ($tasks as $task)
                                        <div
                                             class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-2 h-2 rounded-full"
                                                     style="background-color: {{ $task->project->color }}"></div>
                                                <div>
                                                    <h3 class="font-medium text-gray-900 dark:text-white">
                                                        <a href="{{ route('tasks.show', $task) }}"
                                                           class="hover:text-blue-600 dark:hover:text-blue-400">
                                                            {{ $task->title }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $task->project->name }}</p>
                                                    @if ($task->description)
                                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                                            {{ Str::limit($task->description, 100) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
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
                                                @if ($task->assignee)
                                                    <div class="flex items-center space-x-1">
                                                        <div
                                                             class="h-6 w-6 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                            <span
                                                                  class="text-xs font-medium text-gray-700 dark:text-gray-200">
                                                                {{ $task->assignee->getInitials() }}
                                                            </span>
                                                        </div>
                                                        <span
                                                              class="text-sm text-gray-600 dark:text-gray-300">{{ $task->assignee->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Tasks Pagination -->
                                <div class="mt-6">
                                    {{ $tasks->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @elseif($query && $total === 0)
                <div
                     class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No results found</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            No projects or tasks match your search for "<strong>{{ $query }}</strong>".
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('projects.create') }}" class="btn btn-primary mr-3">
                                Create Project
                            </a>
                            <a href="{{ route('tasks.create') }}" class="btn btn-secondary">
                                Create Task
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div
                     class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Search your projects and tasks
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Enter a search term above to find projects and tasks.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
