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
                                    Create Task: {{ $project->name }}
                                </h2>
                                <p class="text-sm text-gray-600">{{ $project->key }}</p>
                            </div>
                        </div>
                        <a href="{{ route('projects.show', $project) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Project
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('projects.tasks.store', $project) }}"
                          enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Task Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                Task Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
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
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
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
                                    <option value="story" {{ old('type') == 'story' ? 'selected' : '' }}>Story</option>
                                    <option value="bug" {{ old('type') == 'bug' ? 'selected' : '' }}>Bug</option>
                                    <option value="task" {{ old('type') == 'task' ? 'selected' : '' }}>Task</option>
                                    <option value="epic" {{ old('type') == 'epic' ? 'selected' : '' }}>Epic</option>
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
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium
                                    </option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical
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
                                    <option value="todo" {{ old('status', 'todo') == 'todo' ? 'selected' : '' }}>To Do
                                    </option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
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
                                                {{ old('assignee_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                            @if ($user->department)
                                                ({{ $user->department }})
                                            @else
                                                ({{ $user->role }})
                                            @endif
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
                                <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                                       min="{{ date('Y-m-d') }}"
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
                                       value="{{ old('story_points') }}" min="1" max="100"
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
                                <div><strong>Story:</strong> A feature or requirement from the user's perspective</div>
                                <div><strong>Bug:</strong> An issue or defect that needs to be fixed</div>
                                <div><strong>Task:</strong> A general work item or technical task</div>
                                <div><strong>Epic:</strong> A large user story that can be broken down into smaller stories
                                </div>
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
                                Upload files (max 10MB each). Supported formats: JPG, PNG, PDF, DOC, TXT, ZIP
                            </p>
                            @error('attachments')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('attachments.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Priority Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Priority Levels</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <div><strong>Critical:</strong> Urgent issues that block progress</div>
                                <div><strong>High:</strong> Important tasks that should be completed soon</div>
                                <div><strong>Medium:</strong> Standard priority tasks</div>
                                <div><strong>Low:</strong> Nice-to-have features or non-urgent tasks</div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('projects.show', $project) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Create Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
