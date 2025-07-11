@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Edit Project: {{ $project->name }}
                        </h2>
                        <a href="{{ route('projects.show', $project) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Project
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Project Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Project Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}"
                                   required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project Key -->
                        <div>
                            <label for="key" class="block text-sm font-medium text-gray-700">
                                Project Key <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="key" name="key" value="{{ old('key', $project->key) }}"
                                   required maxlength="10" placeholder="e.g., PROJ"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('key') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Short identifier for the project (max 10 characters)</p>
                            @error('key')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('description') border-red-300 @enderror">{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                    <option value="planning"
                                            {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>Planning
                                    </option>
                                    <option value="active"
                                            {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="on_hold"
                                            {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>On Hold
                                    </option>
                                    <option value="completed"
                                            {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled"
                                            {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                                @error('status')
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
                                            {{ old('priority', $project->priority) == 'low' ? 'selected' : '' }}>Low
                                    </option>
                                    <option value="medium"
                                            {{ old('priority', $project->priority) == 'medium' ? 'selected' : '' }}>Medium
                                    </option>
                                    <option value="high"
                                            {{ old('priority', $project->priority) == 'high' ? 'selected' : '' }}>High
                                    </option>
                                    <option value="critical"
                                            {{ old('priority', $project->priority) == 'critical' ? 'selected' : '' }}>
                                        Critical</option>
                                </select>
                                @error('priority')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">
                                    Start Date
                                </label>
                                <input type="date" id="start_date" name="start_date"
                                       value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('start_date') border-red-300 @enderror">
                                @error('start_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">
                                    End Date
                                </label>
                                <input type="date" id="end_date" name="end_date"
                                       value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('end_date') border-red-300 @enderror">
                                @error('end_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Color -->
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700">
                                Project Color
                            </label>
                            <div class="mt-1 flex items-center space-x-3">
                                <input type="color" id="color" name="color"
                                       value="{{ old('color', $project->color ?? '#3B82F6') }}"
                                       class="h-10 w-20 border border-gray-300 rounded-md">
                                <span class="text-sm text-gray-500">Choose a color to identify your project</span>
                            </div>
                            @error('color')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('projects.show', $project) }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Update Project
                                </button>
                            </div>

                            <!-- Delete Button -->
                            <button type="button" onclick="confirmDelete()"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Delete Project
                            </button>
                        </div>
                    </form>

                    <!-- Delete Form (Hidden) -->
                    <form id="delete-form" method="POST" action="{{ route('projects.destroy', $project) }}"
                          class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm(
                    'Are you sure you want to delete this project? This action cannot be undone and will delete all associated tasks.'
                    )) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
@endsection
