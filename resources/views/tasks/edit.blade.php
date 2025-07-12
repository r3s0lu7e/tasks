@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-4 h-4 rounded" style="background-color: {{ $project->color }}"></div>
                            <div>
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                    Edit Task: {{ $task->title }}
                                </h2>
                                <p class="text-sm text-gray-600">{{ $project->key }}-{{ $task->id }}</p>
                            </div>
                        </div>
                        <a href="{{ route('projects.tasks.show', [$project, $task]) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Task
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('projects.tasks.update', [$project, $task]) }}"
                          enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Task Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                Task Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}"
                                   required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('title') border-red-300 @enderror">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('description') border-red-300 @enderror"
                                      placeholder="Enter task description... You can paste images directly here!">{{ old('description', $task->description) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">
                                💡 <strong>Tip:</strong> You can paste screenshots directly into this field! Just copy an
                                image and paste it here.
                            </p>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">
                                    Type <span class="text-red-500">*</span>
                                </label>
                                <select id="type" name="type" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('type') border-red-300 @enderror">
                                    <option value="">Select Type</option>
                                    <option value="story" {{ old('type', $task->type) == 'story' ? 'selected' : '' }}>
                                        Story</option>
                                    <option value="bug" {{ old('type', $task->type) == 'bug' ? 'selected' : '' }}>Bug
                                    </option>
                                    <option value="task" {{ old('type', $task->type) == 'task' ? 'selected' : '' }}>Task
                                    </option>
                                    <option value="epic" {{ old('type', $task->type) == 'epic' ? 'selected' : '' }}>Epic
                                    </option>
                                </select>
                                @error('type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700">
                                    Priority <span class="text-red-500">*</span>
                                </label>
                                <select id="priority" name="priority" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('priority') border-red-300 @enderror">
                                    <option value="">Select Priority</option>
                                    <option value="low"
                                            {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium"
                                            {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium
                                    </option>
                                    <option value="high"
                                            {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High
                                    </option>
                                    <option value="critical"
                                            {{ old('priority', $task->priority) == 'critical' ? 'selected' : '' }}>Critical
                                    </option>
                                </select>
                                @error('priority')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="status" name="status" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('status') border-red-300 @enderror">
                                    <option value="">Select Status</option>
                                    <option value="todo" {{ old('status', $task->status) == 'todo' ? 'selected' : '' }}>
                                        To Do</option>
                                    <option value="in_progress"
                                            {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                    <option value="completed"
                                            {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed
                                    </option>
                                    <option value="cancelled"
                                            {{ old('status', $task->status) == 'cancelled' ? 'selected' : '' }}>Cancelled
                                    </option>
                                </select>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Assignee -->
                            <div>
                                <label for="assignee_id" class="block text-sm font-medium text-gray-700">
                                    Assignee
                                </label>
                                <select id="assignee_id" name="assignee_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('assignee_id') border-red-300 @enderror">
                                    <option value="">Unassigned</option>
                                    @foreach ($assignableUsers as $user)
                                        <option value="{{ $user->id }}"
                                                {{ old('assignee_id', $task->assignee_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->role }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('assignee_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700">
                                    Due Date
                                </label>
                                <input type="date" id="due_date" name="due_date"
                                       value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('due_date') border-red-300 @enderror">
                                @error('due_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Story Points -->
                            <div>
                                <label for="story_points" class="block text-sm font-medium text-gray-700">
                                    Story Points
                                </label>
                                <input type="number" id="story_points" name="story_points"
                                       value="{{ old('story_points', $task->story_points) }}" min="1" max="100"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('story_points') border-red-300 @enderror">
                                <p class="mt-1 text-sm text-gray-500">Estimate effort required (1-100)</p>
                                @error('story_points')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Task Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Task Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                <div>
                                    <strong>Created:</strong> {{ $task->created_at->format('M j, Y \a\t g:i A') }}
                                </div>
                                <div>
                                    <strong>Creator:</strong> {{ $task->creator->name }}
                                </div>
                                <div>
                                    <strong>Last Updated:</strong> {{ $task->updated_at->format('M j, Y \a\t g:i A') }}
                                </div>
                                <div>
                                    <strong>Project:</strong> {{ $project->name }} ({{ $project->key }})
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('projects.tasks.show', [$project, $task]) }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Update Task
                                </button>
                            </div>

                            <!-- Delete Button -->
                            @if (
                                $task->creator_id === auth()->id() ||
                                    $task->assignee_id === auth()->id() ||
                                    $project->owner_id === auth()->id() ||
                                    auth()->user()->isAdmin())
                                <button type="button" onclick="confirmDelete()"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Delete Task
                                </button>
                            @endif
                        </div>
                    </form>

                    <!-- Delete Form (Hidden) -->
                    @if (
                        $task->creator_id === auth()->id() ||
                            $task->assignee_id === auth()->id() ||
                            $project->owner_id === auth()->id() ||
                            auth()->user()->isAdmin())
                        <form id="delete-form" method="POST"
                              action="{{ route('projects.tasks.destroy', [$project, $task]) }}" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm(
                    'Are you sure you want to delete this task? This action cannot be undone and will delete all associated comments and attachments.'
                )) {
                document.getElementById('delete-form').submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize image paste functionality for description field
            new ImagePaste('#description');
        });
    </script>
@endsection
