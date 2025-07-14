@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Edit Profile
                        </h2>
                        <a href="{{ route('profile.show') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Profile Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Profile Information</h3>

                        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                       required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('name') border-red-300 @enderror">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                       required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('email') border-red-300 @enderror">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Role (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Role
                                </label>
                                <div class="mt-1">
                                    <span
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($user->role === 'admin') bg-red-100 text-red-800
                                    @elseif($user->role === 'project_manager') bg-blue-100 text-blue-800
                                    @elseif($user->role === 'developer') bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Contact an administrator to change your role.</p>
                            </div>

                            <!-- Account Information -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Account Information</h4>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <div><strong>Member Since:</strong> {{ $user->created_at->format('d.m.Y') }}</div>
                                    <div><strong>Last Updated:</strong> {{ $user->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('profile.show') }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>

                        <form method="POST" action="{{ route('profile.password') }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">
                                    Current Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="current_password" name="current_password" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('current_password') border-red-300 @enderror">
                                @error('current_password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    New Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="password" name="password" required minlength="8"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('password') border-red-300 @enderror">
                                <p class="mt-1 text-sm text-gray-500">Password must be at least 8 characters long.</p>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    Confirm New Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                       minlength="8"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-jira-blue focus:border-jira-blue sm:text-sm">
                            </div>

                            <!-- Password Requirements -->
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-yellow-800 mb-2">Password Requirements</h4>
                                <ul class="text-sm text-yellow-700 space-y-1">
                                    <li>• At least 8 characters long</li>
                                    <li>• Must be different from your current password</li>
                                    <li>• Confirmation must match the new password</li>
                                </ul>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end">
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if (auth()->user()->isAdmin())
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Settings</h3>
                        <div class="space-y-4">
                            <div>
                                <a href="{{ route('task-statuses.index') }}"
                                   class="text-sm font-medium text-jira-blue hover:text-blue-700">
                                    Manage Task Statuses &rarr;
                                </a>
                                <p class="mt-1 text-sm text-gray-500">
                                    Define the statuses that tasks can be assigned to (e.g., To Do, In Progress, Done).
                                </p>
                            </div>
                            <div class="border-t border-gray-200 my-4"></div>
                            <div>
                                <a href="{{ route('task-types.index') }}"
                                   class="text-sm font-medium text-jira-blue hover:text-blue-700">
                                    Manage Task Types &rarr;
                                </a>
                                <p class="mt-1 text-sm text-gray-500">
                                    Define the types of tasks, like Bug, Story, or Epic, each with a unique icon and color.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Delete Account Section -->
            <div class="mt-6">
                <div class="bg-red-50 border border-red-200 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-red-900 mb-4">Danger Zone</h3>
                        <div class="bg-white p-4 rounded-lg border border-red-200">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h4 class="text-sm font-medium text-red-900">Delete Account</h4>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>Once you delete your account, all of your data will be permanently removed. This
                                            action cannot be undone.</p>
                                        @if (auth()->user()->isAdmin())
                                            <p class="mt-2 font-medium">As an admin, your owned projects will be
                                                transferred to another admin or deleted if you're the last admin.</p>
                                        @endif
                                    </div>
                                    <div class="mt-4">
                                        <button type="button" onclick="showDeleteForm()"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Delete Account
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Confirmation Form (Hidden) -->
                        <div id="delete-form" class="hidden mt-4 bg-white p-4 rounded-lg border border-red-200">
                            <form method="POST" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('DELETE')

                                <div class="mb-4">
                                    <label for="delete_password" class="block text-sm font-medium text-red-900">
                                        Enter your password to confirm deletion <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="delete_password" name="password" required
                                           class="mt-1 block w-full border-red-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('password') border-red-500 @enderror">
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-end space-x-3">
                                    <button type="button" onclick="hideDeleteForm()"
                                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                            onclick="return confirm('Are you absolutely sure? This action cannot be undone and will permanently delete your account and all associated data.')"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Permanently Delete Account
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDeleteForm() {
            document.getElementById('delete-form').classList.remove('hidden');
        }

        function hideDeleteForm() {
            document.getElementById('delete-form').classList.add('hidden');
            document.getElementById('delete_password').value = '';
        }
    </script>
@endsection
