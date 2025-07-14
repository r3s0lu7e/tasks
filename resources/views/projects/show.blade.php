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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <!-- Project Info -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Status</h3>
                            <span
                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if ($project->status === 'active') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                            @elseif($project->status === 'planning') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                            @elseif($project->status === 'on_hold') bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200
                            @elseif($project->status === 'completed') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                            @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 @endif">
                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                            </span>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Priority</h3>
                            <span
                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if ($project->priority === 'critical') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                            @elseif($project->priority === 'high') bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200
                            @elseif($project->priority === 'medium') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                            @else bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 @endif">
                                {{ ucfirst($project->priority) }}
                            </span>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Owner</h3>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $project->owner->name }}</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Members</h3>
                            <div class="flex items-center space-x-2">
                                <span
                                      class="text-sm text-gray-900 dark:text-white">{{ $project->members->count() + 1 }}</span>
                                <a href="{{ route('projects.members', $project) }}"
                                   class="text-xs text-jira-blue dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                    Manage
                                </a>
                            </div>
                        </div>
                    </div>

                    @if ($project->description)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Description</h3>
                            <p class="text-gray-700 dark:text-gray-300">{{ $project->description }}</p>
                        </div>
                    @endif

                    <!-- Project Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
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
                        <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-200">
                                {{ $stats['in_progress_tasks'] }}</div>
                            <div class="text-sm text-yellow-600 dark:text-yellow-200">In Progress</div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-gray-600 dark:text-gray-200">{{ $stats['todo_tasks'] }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-200">To Do</div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-200">{{ $stats['overdue_tasks'] }}
                            </div>
                            <div class="text-sm text-red-600 dark:text-red-200">Overdue</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kanban Board -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-white">Tasks</h3>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('projects.tasks.index', $project) }}"
                               class="text-sm text-jira-blue dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                View All Tasks
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- To Do Column -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 drop-zone" data-status="todo">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                                To Do
                                <span
                                      class="ml-auto bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-200 text-xs px-2 py-1 rounded-full">
                                    {{ $tasksByStatus->get('todo', collect())->count() }}
                                </span>
                            </h4>
                            <div class="space-y-3 task-container">
                                @foreach ($tasksByStatus->get('todo', []) as $task)
                                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md dark:hover:shadow-lg transition-shadow task-card"
                                         draggable="true" data-task-id="{{ $task->id }}">
                                        <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="block">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                                        {{ $task->title }}
                                                    </h5>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                        {{ $task->type }} •
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

                        <!-- In Progress Column -->
                        <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4 drop-zone" data-status="in_progress">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <span class="w-3 h-3 bg-blue-400 rounded-full mr-2"></span>
                                In Progress
                                <span
                                      class="ml-auto bg-blue-200 dark:bg-blue-800 text-blue-600 dark:text-blue-200 text-xs px-2 py-1 rounded-full">
                                    {{ $tasksByStatus->get('in_progress', collect())->count() }}
                                </span>
                            </h4>
                            <div class="space-y-3 task-container">
                                @foreach ($tasksByStatus->get('in_progress', []) as $task)
                                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md dark:hover:shadow-lg transition-shadow task-card"
                                         draggable="true" data-task-id="{{ $task->id }}">
                                        <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="block">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                                        {{ $task->title }}
                                                    </h5>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                        {{ $task->type }} •
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

                        <!-- Completed Column -->
                        <div class="bg-green-50 dark:bg-green-900 rounded-lg p-4 drop-zone" data-status="completed">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                                Completed
                                <span
                                      class="ml-auto bg-green-200 dark:bg-green-800 text-green-600 dark:text-green-200 text-xs px-2 py-1 rounded-full">
                                    {{ $tasksByStatus->get('completed', collect())->count() }}
                                </span>
                            </h4>
                            <div class="space-y-3 task-container">
                                @foreach ($tasksByStatus->get('completed', []) as $task)
                                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md dark:hover:shadow-lg transition-shadow task-card"
                                         draggable="true" data-task-id="{{ $task->id }}">
                                        <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="block">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                                        {{ $task->title }}
                                                    </h5>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                        {{ $task->type }} •
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

                        <!-- Cancelled Column -->
                        <div class="bg-red-50 dark:bg-red-900 rounded-lg p-4 drop-zone" data-status="cancelled">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                                Cancelled
                                <span
                                      class="ml-auto bg-red-200 dark:bg-red-800 text-red-600 dark:text-red-200 text-xs px-2 py-1 rounded-full">
                                    {{ $tasksByStatus->get('cancelled', collect())->count() }}
                                </span>
                            </h4>
                            <div class="space-y-3 task-container">
                                @foreach ($tasksByStatus->get('cancelled', []) as $task)
                                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md dark:hover:shadow-lg transition-shadow task-card"
                                         draggable="true" data-task-id="{{ $task->id }}">
                                        <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="block">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                                        {{ $task->title }}
                                                    </h5>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                        {{ $task->type }} •
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

            // Get all draggable task cards
            const taskCards = document.querySelectorAll('.task-card');
            const dropZones = document.querySelectorAll('.drop-zone');

            // Add drag event listeners to task cards
            taskCards.forEach(card => {
                card.addEventListener('dragstart', handleDragStart);
                card.addEventListener('dragend', handleDragEnd);
            });

            // Add drop event listeners to drop zones
            dropZones.forEach(zone => {
                zone.addEventListener('dragover', handleDragOver);
                zone.addEventListener('drop', handleDrop);
                zone.addEventListener('dragleave', handleDragLeave);
                zone.addEventListener('dragenter', handleDragEnter);
            });

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

                const newStatus = this.getAttribute('data-status');
                const taskContainer = this.querySelector('.task-container');

                if (draggedElement && draggedTaskId && taskContainer) {
                    // Update task status via AJAX
                    updateTaskStatus(draggedTaskId, newStatus, () => {
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

            function updateTaskStatus(taskId, newStatus, onSuccess) {
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/tasks/${taskId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            onSuccess();

                            // Show success message (optional)
                            showNotification('Task status updated successfully', 'success');
                        } else {
                            showNotification('Failed to update task status', 'error');
                            // Reload page to reset state
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred while updating task status', 'error');
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
            background-color: rgba(59, 130, 246, 0.1) !important;
            border: 2px dashed rgba(59, 130, 246, 0.5);
        }

        .task-container {
            min-height: 100px;
        }
    </style>
@endpush
