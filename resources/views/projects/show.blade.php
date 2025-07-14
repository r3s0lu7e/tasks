@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Project Header -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-4 h-4 rounded" style="background-color: {{ $project->color }}"></div>
                            <div>
                                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                                    {{ $project->name }}
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $project->key }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('projects.tasks.create', $project) }}"
                               class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Task
                            </a>
                            @if ($project->owner_id === auth()->id() || auth()->user()->isAdmin())
                                <a href="{{ route('projects.edit', $project) }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Edit Project
                                </a>
                            @endif
                            <a href="{{ route('projects.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Back to Projects
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Project Details -->
                <div class="p-6">
                    <!-- Project Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-200">{{ $stats['total_tasks'] }}
                            </div>
                            <div class="text-sm text-blue-600 dark:text-blue-200">Total Tasks</div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-200">
                                {{ $stats['completed_tasks'] }}</div>
                            <div class="text-sm text-green-600 dark:text-green-200">Completed</div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-200">{{ $stats['overdue_tasks'] }}
                            </div>
                            <div class="text-sm text-red-600 dark:text-red-200">Overdue</div>
                        </div>
                    </div>

                    @if ($project->description)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Description</h3>
                            <p class="text-gray-700 dark:text-gray-300">{{ $project->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kanban Board -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-white">Tasks</h3>
                        <div class="flex items-center space-x-3">
                            <!-- View Toggle Switch -->
                            <div class="flex items-center space-x-2">
                                <label class="text-sm text-gray-600 dark:text-gray-400">Status View</label>
                                <button type="button" id="viewToggle"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 dark:bg-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-jira-blue focus:ring-offset-2"
                                        role="switch" aria-checked="false">
                                    <span class="sr-only">Toggle view</span>
                                    <span id="toggleButton"
                                          class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-1">
                                    </span>
                                </button>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Types View</label>
                            </div>
                            <a href="{{ route('projects.tasks.index', $project) }}"
                               class="text-sm text-jira-blue dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                View All Tasks
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Status View (Default) -->
                    <div id="statusView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ count($statuses) }} gap-6">
                        @foreach ($statuses as $status)
                            <div class="rounded-lg p-4 drop-zone"
                                 style="background-color: rgba({{ $status->rgb_color }}, 0.1)"
                                 data-status-id="{{ $status->id }}">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                    <span class="w-3 h-3 rounded-full mr-2"
                                          style="background-color: {{ $status->color }}"></span>
                                    {{ $status->name }}
                                    <span
                                          class="ml-auto bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-200 text-xs px-2 py-1 rounded-full">
                                        {{ $tasksByStatus->get($status->id, collect())->count() }}
                                    </span>
                                </h4>
                                <div class="space-y-3 task-container">
                                    @foreach ($tasksByStatus->get($status->id, []) as $task)
                                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md dark:hover:shadow-lg transition-shadow task-card"
                                             draggable="true" data-task-id="{{ $task->id }}">
                                            <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="block">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                                            {{ $task->title }}
                                                        </h5>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                            {{ $task->type->name }} •
                                                            {{ $project->key }}-{{ $task->id }}</p>
                                                        <div class="flex items-center space-x-2">
                                                            <span
                                                                  class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                                @if ($task->priority === 'critical') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                                                @elseif($task->priority === 'high') bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200
                                                                @elseif($task->priority === 'medium') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                                                @else bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 @endif">
                                                                {{ ucfirst($task->priority) }}
                                                            </span>
                                                            @if ($task->assignee)
                                                                <span
                                                                      class="text-xs text-gray-500 dark:text-gray-400">{{ $task->assignee->name }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Types View (Hidden by default) -->
                    <div id="typesView"
                         class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ count($types) + 1 }} gap-6 hidden">
                        @foreach ($types as $type)
                            <div class="rounded-lg p-4 drop-zone"
                                 style="background-color: rgba({{ $type->rgb_color }}, 0.1)"
                                 data-type-id="{{ $type->id }}">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                    <span class="w-3 h-3 rounded-full mr-2"
                                          style="background-color: {{ $type->color }}"></span>
                                    {{ $type->name }}
                                    <span
                                          class="ml-auto bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-200 text-xs px-2 py-1 rounded-full">
                                        {{ $project->tasks->where('task_type_id', $type->id)->where('status.alias', '!=', 'completed')->count() }}
                                    </span>
                                </h4>
                                <div class="space-y-3 task-container">
                                    @foreach ($project->tasks->where('task_type_id', $type->id)->where('status.alias', '!=', 'completed') as $task)
                                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md dark:hover:shadow-lg transition-shadow task-card"
                                             draggable="true" data-task-id="{{ $task->id }}">
                                            <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="block">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                                            {{ $task->title }}
                                                        </h5>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                            {{ $task->status->name }} •
                                                            {{ $project->key }}-{{ $task->id }}</p>
                                                        <div class="flex items-center space-x-2">
                                                            <span
                                                                  class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                                @if ($task->priority === 'critical') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                                                @elseif($task->priority === 'high') bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200
                                                                @elseif($task->priority === 'medium') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                                                @else bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 @endif">
                                                                {{ ucfirst($task->priority) }}
                                                            </span>
                                                            @if ($task->assignee)
                                                                <span
                                                                      class="text-xs text-gray-500 dark:text-gray-400">{{ $task->assignee->name }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <!-- Completed Column -->
                        @php
                            $completedStatus = $statuses->where('alias', 'completed')->first();
                        @endphp
                        @if ($completedStatus)
                            <div class="rounded-lg p-4 drop-zone"
                                 style="background-color: rgba({{ $completedStatus->rgb_color }}, 0.1)"
                                 data-status-id="{{ $completedStatus->id }}">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                    <span class="w-3 h-3 rounded-full mr-2"
                                          style="background-color: {{ $completedStatus->color }}"></span>
                                    {{ $completedStatus->name }}
                                    <span
                                          class="ml-auto bg-green-200 dark:bg-green-800 text-green-600 dark:text-green-200 text-xs px-2 py-1 rounded-full">
                                        {{ $project->tasks->where('task_status_id', $completedStatus->id)->count() }}
                                    </span>
                                </h4>
                                <div class="space-y-3 task-container">
                                    @foreach ($project->tasks->where('task_status_id', $completedStatus->id)->take(10) as $task)
                                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md dark:hover:shadow-lg transition-shadow task-card opacity-75"
                                             draggable="true" data-task-id="{{ $task->id }}">
                                            <a href="{{ route('projects.tasks.show', [$project, $task]) }}"
                                               class="block">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <h5
                                                            class="text-sm font-medium text-gray-900 dark:text-white mb-1 line-through">
                                                            {{ $task->title }}
                                                        </h5>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                            {{ $task->type->name }} •
                                                            {{ $project->key }}-{{ $task->id }}</p>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                                                  style="background-color: rgba({{ $task->status->rgb_color }}, 0.2); color: {{ $task->status->color }}">
                                                                {{ $task->status->name }}
                                                            </span>
                                                            @if ($task->assignee)
                                                                <span
                                                                      class="text-xs text-gray-500 dark:text-gray-400">{{ $task->assignee->name }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let draggedElement = null;
            let draggedTaskId = null;
            let currentView = 'status'; // Track current view

            // View toggle functionality
            const viewToggle = document.getElementById('viewToggle');
            const toggleButton = document.getElementById('toggleButton');
            const statusView = document.getElementById('statusView');
            const typesView = document.getElementById('typesView');

            viewToggle.addEventListener('click', function() {
                if (currentView === 'status') {
                    // Switch to types view
                    currentView = 'types';
                    statusView.classList.add('hidden');
                    typesView.classList.remove('hidden');
                    toggleButton.classList.remove('translate-x-1');
                    toggleButton.classList.add('translate-x-6');
                    viewToggle.classList.remove('bg-gray-200', 'dark:bg-gray-600');
                    viewToggle.classList.add('bg-jira-blue');
                    viewToggle.setAttribute('aria-checked', 'true');
                } else {
                    // Switch to status view
                    currentView = 'status';
                    typesView.classList.add('hidden');
                    statusView.classList.remove('hidden');
                    toggleButton.classList.remove('translate-x-6');
                    toggleButton.classList.add('translate-x-1');
                    viewToggle.classList.remove('bg-jira-blue');
                    viewToggle.classList.add('bg-gray-200', 'dark:bg-gray-600');
                    viewToggle.setAttribute('aria-checked', 'false');
                }

                // Reinitialize drag and drop for the new view
                initializeDragAndDrop();
            });

            // Initialize drag and drop
            initializeDragAndDrop();

            function initializeDragAndDrop() {
                // Get all draggable task cards
                const taskCards = document.querySelectorAll('.task-card');
                const dropZones = document.querySelectorAll('.drop-zone');

                // Add drag event listeners to task cards
                taskCards.forEach(card => {
                    card.removeEventListener('dragstart', handleDragStart);
                    card.removeEventListener('dragend', handleDragEnd);
                    card.addEventListener('dragstart', handleDragStart);
                    card.addEventListener('dragend', handleDragEnd);
                });

                // Add drop event listeners to drop zones
                dropZones.forEach(zone => {
                    zone.removeEventListener('dragover', handleDragOver);
                    zone.removeEventListener('drop', handleDrop);
                    zone.removeEventListener('dragleave', handleDragLeave);
                    zone.removeEventListener('dragenter', handleDragEnter);

                    zone.addEventListener('dragover', handleDragOver);
                    zone.addEventListener('drop', handleDrop);
                    zone.addEventListener('dragleave', handleDragLeave);
                    zone.addEventListener('dragenter', handleDragEnter);
                });
            }

            function handleDragStart(e) {
                draggedElement = this;
                draggedTaskId = this.getAttribute('data-task-id');
                this.style.opacity = '0.5';
                this.classList.add('dragging');

                // Store the drag data
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.innerHTML);
            }

            function handleDragEnd(e) {
                this.style.opacity = '';
                this.classList.remove('dragging');

                // Remove all drag-over classes
                const dropZones = document.querySelectorAll('.drop-zone');
                dropZones.forEach(zone => {
                    zone.classList.remove('drag-over');
                });
            }

            function handleDragOver(e) {
                if (e.preventDefault) {
                    e.preventDefault();
                }
                e.dataTransfer.dropEffect = 'move';
                return false;
            }

            function handleDragEnter(e) {
                this.classList.add('drag-over');
            }

            function handleDragLeave(e) {
                if (e.target === this) {
                    this.classList.remove('drag-over');
                }
            }

            function handleDrop(e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }
                e.preventDefault();

                this.classList.remove('drag-over');

                const taskContainer = this.querySelector('.task-container');

                if (draggedElement && draggedTaskId && taskContainer) {
                    let updateData = {};

                    if (currentView === 'status') {
                        const newStatusId = this.getAttribute('data-status-id');
                        updateData = {
                            status_id: newStatusId
                        };
                    } else {
                        const newTypeId = this.getAttribute('data-type-id');
                        if (newTypeId) {
                            updateData = {
                                type_id: newTypeId
                            };
                        } else {
                            // This is the completed column
                            const completedStatusId = this.getAttribute('data-status-id');
                            updateData = {
                                status_id: completedStatusId
                            };
                        }
                    }

                    // Update task via AJAX
                    updateTask(draggedTaskId, updateData, () => {
                        // Move the element to the new column
                        taskContainer.appendChild(draggedElement);

                        // Update the count in the column header
                        updateColumnCount(this);

                        // Update the count in the original column
                        const originalColumn = draggedElement.closest('.drop-zone');
                        if (originalColumn && originalColumn !== this) {
                            updateColumnCount(originalColumn);
                        }
                    });
                }

                return false;
            }

            function updateTask(taskId, updateData, onSuccess) {
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/tasks/${taskId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(updateData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            onSuccess();

                            // Show success message (optional)
                            showNotification('Task updated successfully', 'success');
                        } else {
                            showNotification('Failed to update task', 'error');
                            // Reload page to reset state
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred while updating task', 'error');
                        // Reload page to reset state
                        window.location.reload();
                    });
            }

            function updateColumnCount(column) {
                const countBadge = column.querySelector('h4 span:last-child');
                const taskCount = column.querySelectorAll('.task-card').length;
                if (countBadge) {
                    countBadge.textContent = taskCount;
                }
            }

            function showNotification(message, type) {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
                notification.textContent = message;

                // Add to body
                document.body.appendChild(notification);

                // Remove after 3 seconds
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 3000);
            }
        });
    </script>

    <style>
        .task-card {
            cursor: move;
            transition: all 0.3s ease;
        }

        .task-card:hover {
            transform: translateY(-2px);
        }

        .task-card.dragging {
            cursor: grabbing;
        }

        .drop-zone {
            min-height: 200px;
            transition: all 0.3s ease;
        }

        .drop-zone.drag-over {
            border: 2px dashed rgba(59, 130, 246, 0.5);
        }

        .task-container {
            min-height: 100px;
        }

        /* Toggle button transitions */
        #toggleButton {
            transition: transform 0.2s ease-in-out;
        }

        #viewToggle {
            transition: background-color 0.2s ease-in-out;
        }
    </style>
@endpush
