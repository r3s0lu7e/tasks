@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Task Header -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div
                                 class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full text-sm font-medium
                            @if ($task->type === 'story') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                            @elseif($task->type === 'bug') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                            @elseif($task->type === 'epic') bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200
                            @else bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 @endif">
                                {{ strtoupper(substr($task->type, 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                                    {{ $task->title }}
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $project->key }}-{{ $task->id }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Edit Task
                            </a>
                            <a href="{{ route('projects.tasks.index', $project) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Back to Tasks
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Task Details -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <!-- Task Info -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Status</h3>
                            <span class="status-badge status-{{ str_replace('_', '-', $task->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Priority</h3>
                            <span class="priority-badge priority-{{ $task->priority }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Type</h3>
                            <span
                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if ($task->type === 'story') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                            @elseif($task->type === 'bug') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                            @elseif($task->type === 'epic') bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200
                            @else bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 @endif">
                                {{ ucfirst($task->type) }}
                            </span>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Assignee</h3>
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</p>
                        </div>
                    </div>

                    @if ($task->description)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Description</h3>
                            <div class="prose dark:prose-invert max-w-none">
                                <p class="text-gray-700 dark:text-gray-300">{{ $task->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Task Metadata -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Project</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded" style="background-color: {{ $project->color }}"></div>
                                <a href="{{ route('projects.show', $project) }}"
                                   class="text-sm text-gray-900 dark:text-white hover:text-jira-blue dark:hover:text-blue-400">
                                    {{ $project->name }}
                                </a>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Due Date</h3>
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $task->due_date ? $task->due_date->format('M j, Y') : 'No due date' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Created</h3>
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $task->created_at->format('M j, Y \a\t g:i A') }}
                            </p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Last Updated</h3>
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $task->updated_at->format('M j, Y \a\t g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Attachments -->
            @if ($task->attachments->count() > 0)
                <div
                     class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                    <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-white">Attachments</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($task->attachments as $attachment)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none"
                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $attachment->original_name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $attachment->file_size_human }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('attachments.download', $attachment) }}"
                                           class="text-jira-blue dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Task Comments -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="font-semibold text-lg text-gray-800 dark:text-white">Comments</h3>
                </div>
                <div class="p-6">
                    @if ($task->comments->count() > 0)
                        <div id="comments-container" class="space-y-6">
                            @foreach ($task->comments as $comment)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                 class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-200">
                                                    {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $comment->user->name }}</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $comment->created_at->diffForHumans() }}</p>
                                            </div>
                                            <div class="mt-2 prose dark:prose-invert max-w-none">
                                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $comment->content }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div id="comments-container" class="space-y-6">
                            <p id="no-comments" class="text-gray-500 dark:text-gray-400 text-center py-8">No comments yet.
                            </p>
                        </div>
                    @endif

                    <!-- Add Comment Form -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <form id="comment-form" method="POST" action="{{ route('tasks.comments.store', $task) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="content"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Add a comment
                                </label>
                                <textarea name="content" id="content" rows="3" required
                                          class="block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue"
                                          placeholder="Write your comment here..."></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" id="comment-submit" class="btn btn-primary">
                                    Add Comment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // console.log('Comment form script loaded');

            const commentForm = document.getElementById('comment-form');
            const commentsContainer = document.getElementById('comments-container');
            const noCommentsMessage = document.getElementById('no-comments');
            const submitButton = document.getElementById('comment-submit');
            const contentTextarea = document.getElementById('content');

            // Fallback for showNotification if not available
            if (typeof window.showNotification !== 'function') {
                window.showNotification = function(message, type = 'success') {
                    alert(message);
                };
            }

            if (!commentForm) {
                console.error('Comment form not found');
                return;
            }

            // console.log('Comment form found, adding event listener');

            commentForm.addEventListener('submit', function(e) {
                // console.log('Form submitted');
                e.preventDefault();

                const formData = new FormData(commentForm);
                const submitText = submitButton.textContent;

                // console.log('Form data:', Object.fromEntries(formData));
                // console.log('Form action:', commentForm.action);

                // Disable submit button and show loading state
                submitButton.disabled = true;
                submitButton.textContent = 'Adding...';

                fetch(commentForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => {
                        // console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        // console.log('Response data:', data);

                        if (data.success) {
                            // Remove "No comments yet" message if it exists
                            if (noCommentsMessage) {
                                noCommentsMessage.remove();
                            }

                            // Create new comment element
                            const commentDiv = document.createElement('div');
                            commentDiv.className = 'bg-gray-50 dark:bg-gray-700 p-4 rounded-lg';
                            commentDiv.innerHTML = `
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">
                                            ${data.comment.user.initials}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                            ${data.comment.user.name}
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            ${data.comment.created_at}
                                        </p>
                                    </div>
                                    <div class="mt-2 prose dark:prose-invert max-w-none">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            ${data.comment.content}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;

                            // Add the new comment to the container
                            commentsContainer.appendChild(commentDiv);

                            // Clear the form
                            contentTextarea.value = '';

                            // Show success notification
                            showNotification(data.message, 'success');

                            // console.log('Comment added successfully');
                        } else {
                            // console.error('Server returned error:', data);
                            showNotification('Failed to add comment. Please try again.', 'error');
                        }
                    })
                    .catch(error => {
                        // console.error('Fetch error:', error);
                        showNotification('An error occurred. Please try again.', 'error');
                    })
                    .finally(() => {
                        // Re-enable submit button
                        submitButton.disabled = false;
                        submitButton.textContent = submitText;
                    });
            });
        });
    </script>
@endsection
