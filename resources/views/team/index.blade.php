@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                            Team Members
                        </h2>
                        <a href="{{ route('team.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add Team Member
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('team.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Name, email, or department..."
                                   class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select name="status" id="status"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses
                                </option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}"
                                            {{ request('status') === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Role Filter -->
                        <div>
                            <label for="role"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                            <select name="role" id="role"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="all" {{ request('role') === 'all' ? 'selected' : '' }}>All Roles</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $role)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Button -->
                        <div class="flex items-end space-x-2">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 flex-1">
                                Filter
                            </button>
                            <a href="{{ route('team.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Team Members Grid -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($teamMembers->count() > 0)
                        <!-- Results Summary -->
                        <div class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                            Showing {{ $teamMembers->count() }} team member{{ $teamMembers->count() !== 1 ? 's' : '' }}
                            @if (request()->hasAny(['search', 'status', 'role']))
                                <span class="ml-2">
                                    @if (request('search'))
                                        • Search: "{{ request('search') }}"
                                    @endif
                                    @if (request('status') && request('status') !== 'all')
                                        • Status: {{ ucfirst(request('status')) }}
                                    @endif
                                    @if (request('role') && request('role') !== 'all')
                                        • Role: {{ ucfirst(str_replace('_', ' ', request('role'))) }}
                                    @endif
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($teamMembers as $member)
                                <div
                                     class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center">
                                                <div class="h-12 w-12 rounded-full flex items-center justify-center"
                                                     style="background-color: rgba({{ $member->status_color_rgb }}, 0.2)">
                                                    <span class="text-lg font-medium"
                                                          style="color: {{ $member->status_color }}">
                                                        {{ $member->getInitials() }}
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                        <a href="{{ route('team.show', $member) }}"
                                                           class="hover:text-blue-600 dark:hover:text-blue-400">
                                                            {{ $member->name }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $member->email }}</p>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                  style="background-color: rgba({{ $member->status_color_rgb }}, 0.2); color: {{ $member->status_color }}">
                                                {{ ucfirst($member->status) }}
                                            </span>
                                        </div>

                                        <div class="space-y-2 mb-4">
                                            @if ($member->department)
                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    <span class="font-medium">Department:</span> {{ $member->department }}
                                                </p>
                                            @endif
                                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                                <span class="font-medium">Role:</span>
                                                {{ ucfirst(str_replace('_', ' ', $member->role)) }}
                                            </p>
                                        </div>

                                        <div class="grid grid-cols-3 gap-4 mb-4">
                                            <div class="text-center">
                                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ $member->assigned_tasks_count }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Assigned Tasks</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ $member->getCurrentWorkload() }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Active Tasks</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ $member->getCompletionRate() }}%</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Completion Rate</div>
                                            </div>
                                        </div>

                                        <div class="flex space-x-2">
                                            <a href="{{ route('team.show', $member) }}"
                                               class="flex-1 text-center bg-blue-600 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                                                View Details
                                            </a>
                                            <a href="{{ route('team.edit', $member) }}"
                                               class="flex-1 text-center bg-gray-600 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition-colors">
                                                Edit
                                            </a>
                                            @if ($member->id !== auth()->id())
                                                <form method="POST" action="{{ route('team.destroy', $member) }}"
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to remove this team member? Their tasks will be reassigned to you.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-red-700 transition-colors">
                                                        Remove
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            @if (request()->hasAny(['search', 'status', 'role']))
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No team members found
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search or
                                    filters.</p>
                                <div class="mt-6">
                                    <a href="{{ route('team.index') }}"
                                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Clear Filters
                                    </a>
                                </div>
                            @else
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No team members yet</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding your first
                                    team member.</p>
                                <div class="mt-6">
                                    <a href="{{ route('team.create') }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Add Team Member
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
