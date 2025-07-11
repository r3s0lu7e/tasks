@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Task Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div
                                 class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full text-sm font-medium
                            @if ($task->type === 'story') bg-green-100 text-green-800
                            @elseif($task->type === 'bug') bg-red-100 text-red-800
                            @elseif($task->type === 'epic') bg-purple-100 text-purple-800
                            @else bg-blue-100 text-blue-800 @endif">
                                {{ strtoupper(substr($task->type, 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                    {{ $task->title }}
                                </h2>
                                <p class="text-sm text-gray-600">{{ $project->key }}-{{ $task->id }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Edit Task Button -->
                            <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                               class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                               title="Edit Task">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Edit Task
                            </a>

                            <!-- Back to Tasks Button -->
                            <a href="{{ route('projects.tasks.index', $project) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                               title="Back to Tasks">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Tasks
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Task Details -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Task Details</h3>

                            @if ($task->description)
                                <div class="mb-6">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                                    <div class="text-gray-700 whitespace-pre-wrap">{{ $task->description }}</div>
                                </div>
                            @endif

                            <!-- Task Metadata -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Type</h4>
                                    <span
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($task->type === 'story') bg-green-100 text-green-800
                                    @elseif($task->type === 'bug') bg-red-100 text-red-800
                                    @elseif($task->type === 'epic') bg-purple-100 text-purple-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst($task->type) }}
                                    </span>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Priority</h4>
                                    <span
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($task->priority === 'critical') bg-red-100 text-red-800
                                    @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                    @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Status</h4>
                                    <span
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($task->status === 'completed') bg-green-100 text-green-800
                                    @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($task->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </div>

                                @if ($task->story_points)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Story Points</h4>
                                        <span class="text-sm text-gray-900">{{ $task->story_points }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Dates -->
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Created</h4>
                                    <p class="text-sm text-gray-900">{{ $task->created_at->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Last Updated</h4>
                                    <p class="text-sm text-gray-900">{{ $task->updated_at->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>

                                @if ($task->due_date)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Due Date</h4>
                                        <p
                                           class="text-sm @if ($task->due_date < now() && !in_array($task->status, ['completed', 'cancelled'])) text-red-600 @else text-gray-900 @endif">
                                            {{ $task->due_date->format('M j, Y') }}
                                            @if ($task->due_date < now() && !in_array($task->status, ['completed', 'cancelled']))
                                                <span class="text-red-600 font-medium">(Overdue)</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Comments</h3>

                            <!-- Add Comment Form -->
                            <form method="POST" action="{{ route('tasks.comments.store', $task) }}" class="mb-6">
                                @csrf
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-jira-blue flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <textarea name="comment" rows="3" placeholder="Add a comment..."
                                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm"
                                                  required></textarea>
                                        <div class="mt-2">
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Add Comment
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Comments List -->
                            <div class="space-y-4">
                                @forelse($task->comments as $comment)
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <span
                                                      class="text-sm font-medium text-gray-900">{{ $comment->user->name }}</span>
                                                <span
                                                      class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="mt-1 text-sm text-gray-700 whitespace-pre-wrap">
                                                {{ $comment->comment }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-sm">No comments yet. Be the first to comment!</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="lg:col-span-1">
                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>

                            <!-- Status Update -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                                <select id="status-update"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm">
                                    <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                    <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled" {{ $task->status === 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>

                            <!-- Assignee Update -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                                <select id="assignee-update"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm">
                                    <option value="">Unassigned</option>
                                    @foreach ($assignableUsers as $user)
                                        <option value="{{ $user->id }}"
                                                {{ $task->assignee_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Task Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Task Information</h3>

                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Project</h4>
                                    <a href="{{ route('projects.show', $project) }}"
                                       class="text-sm text-jira-blue hover:text-blue-700">
                                        {{ $project->name }}
                                    </a>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Creator</h4>
                                    <p class="text-sm text-gray-900">{{ $task->creator->name }}</p>
                                </div>

                                @if ($task->assignee)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700">Assignee</h4>
                                        <div class="flex items-center space-x-2">
                                            <div class="h-6 w-6 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-700">
                                                    {{ strtoupper(substr($task->assignee->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <span class="text-sm text-gray-900">{{ $task->assignee->name }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Delete Task -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Danger Zone</h3>
                            <button type="button" onclick="confirmDelete()"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Delete Task
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="delete-form" method="POST" action="{{ route('projects.tasks.destroy', [$project, $task]) }}"
          class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Status update
        document.getElementById('status-update').addEventListener('change', function() {
            const status = this.value;
            fetch(`/tasks/{{ $task->id }}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating status');
                    }
                });
        });

        // Assignee update
        document.getElementById('assignee-update').addEventListener('change', function() {
            const assigneeId = this.value;
            fetch(`/tasks/{{ $task->id }}/assign`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        assignee_id: assigneeId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating assignee');
                    }
                });
        });

        // Delete confirmation
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this task? This action cannot be undone.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
@endsection
