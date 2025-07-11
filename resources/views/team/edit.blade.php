@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Edit Team Member: {{ $team->name }}
                        </h2>
                        <a href="{{ route('team.show', $team) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Member
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('team.update', $team) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $team->name) }}"
                                       required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email', $team->email) }}"
                                       required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Department -->
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700">
                                    Department
                                </label>
                                <input type="text" id="department" name="department"
                                       value="{{ old('department', $team->department) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('department') border-red-500 @enderror">
                                @error('department')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">
                                    Phone Number
                                </label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', $team->phone) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select id="role" name="role" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('role') border-red-500 @enderror">
                                    <option value="">Select a role</option>
                                    <option value="developer"
                                            {{ old('role', $team->role) == 'developer' ? 'selected' : '' }}>Developer
                                    </option>
                                    <option value="designer"
                                            {{ old('role', $team->role) == 'designer' ? 'selected' : '' }}>Designer
                                    </option>
                                    <option value="tester" {{ old('role', $team->role) == 'tester' ? 'selected' : '' }}>
                                        Tester
                                    </option>
                                    <option value="project_manager"
                                            {{ old('role', $team->role) == 'project_manager' ? 'selected' : '' }}>
                                        Project Manager</option>
                                    <option value="client" {{ old('role', $team->role) == 'client' ? 'selected' : '' }}>
                                        Client
                                    </option>
                                </select>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="status" name="status" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('status') border-red-500 @enderror">
                                    <option value="active"
                                            {{ old('status', $team->status) == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive"
                                            {{ old('status', $team->status) == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                    <option value="vacation"
                                            {{ old('status', $team->status) == 'vacation' ? 'selected' : '' }}>On
                                        Vacation</option>
                                    <option value="busy" {{ old('status', $team->status) == 'busy' ? 'selected' : '' }}>
                                        Busy
                                    </option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hourly Rate -->
                            <div>
                                <label for="hourly_rate" class="block text-sm font-medium text-gray-700">
                                    Hourly Rate ($)
                                </label>
                                <input type="number" id="hourly_rate" name="hourly_rate"
                                       value="{{ old('hourly_rate', $team->hourly_rate) }}" step="0.01" min="0"
                                       max="999.99"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('hourly_rate') border-red-500 @enderror">
                                @error('hourly_rate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hire Date -->
                            <div>
                                <label for="hire_date" class="block text-sm font-medium text-gray-700">
                                    Hire Date
                                </label>
                                <input type="date" id="hire_date" name="hire_date"
                                       value="{{ old('hire_date', $team->hire_date ? $team->hire_date->format('Y-m-d') : '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('hire_date') border-red-500 @enderror">
                                @error('hire_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">
                                Notes
                            </label>
                            <textarea id="notes" name="notes" rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('notes') border-red-500 @enderror">{{ old('notes', $team->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('team.show', $team) }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jira-blue">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-jira-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jira-blue">
                                    Update Team Member
                                </button>
                            </div>

                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('team.destroy', $team) }}" class="inline-block"
                                  onsubmit="return confirm('Are you sure you want to remove this team member? Their tasks will be reassigned to you.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Remove Member
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
