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
                                <label for="task_type_id" class="block text-sm font-medium text-gray-700">
                                    Type <span class="text-red-500">*</span>
                                </label>
                                <select id="task_type_id" name="task_type_id" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('task_type_id') border-red-300 @enderror">
                                    <option value="">Select Type</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}"
                                                {{ old('task_type_id', $task->task_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('task_type_id')
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
                                <label for="task_status_id" class="block text-sm font-medium text-gray-700">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="task_status_id" name="task_status_id" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('task_status_id') border-red-300 @enderror">
                                    <option value="">Select Status</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}"
                                                {{ old('task_status_id', $task->task_status_id) == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('task_status_id')
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

                        <!-- Task Type Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Task Types</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                @foreach ($types as $type)
                                    <div><strong>{{ $type->name }}:</strong> A brief description of this type.</div>
                                @endforeach
                            </div>
                        </div>

                        <!-- File Attachments -->
                        <div>
                            <label for="attachments" class="block text-sm font-medium text-gray-700">
                                Attachments
                            </label>
                            <input type="file" id="attachments" name="attachments[]" multiple
                                   accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.zip,.rar"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('attachments') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">
                                Upload new files (max 10MB each). Supported formats: JPG, PNG, PDF, DOC, TXT, ZIP
                            </p>
                            @error('attachments')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('attachments.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Existing Attachments -->
                        @if ($task->attachments->count() > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Current Attachments
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($task->attachments as $attachment)
                                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <div class="flex-shrink-0">
                                                        @if (str_starts_with($attachment->mime_type, 'image/'))
                                                            <svg class="h-6 w-6 text-blue-500" fill="none"
                                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                        @elseif (str_starts_with($attachment->mime_type, 'application/pdf'))
                                                            <svg class="h-6 w-6 text-red-500" fill="none"
                                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                        @else
                                                            <svg class="h-6 w-6 text-gray-400" fill="none"
                                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                </path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $attachment->original_filename }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $attachment->file_size_human }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <form method="POST"
                                                      action="{{ route('tasks.attachments.delete', [$task, $attachment]) }}"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700"
                                                            onclick="return confirm('Are you sure you want to delete this attachment?')"
                                                            title="Delete attachment">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                             viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Task Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Task Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                <div>
                                    <strong>Created:</strong> {{ $task->created_at->format('d.m.Y H:i') }}
                                </div>
                                <div>
                                    <strong>Creator:</strong> {{ $task->creator->name }}
                                </div>
                                <div>
                                    <strong>Last Updated:</strong> {{ $task->updated_at->format('d.m.Y H:i') }}
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
