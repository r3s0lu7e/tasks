@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Team Members
                        </h2>
                        <a href="{{ route('team.create') }}" class="btn btn-primary">
                            Add Team Member
                        </a>
                    </div>
                </div>
            </div>

            <!-- Team Members Grid -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($teamMembers->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($teamMembers as $member)
                                <div
                                     class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center">
                                                <div
                                                     class="h-12 w-12 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <span class="text-lg font-medium text-gray-700">
                                                        {{ $member->getInitials() }}
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <h3 class="text-lg font-semibold text-gray-900">
                                                        <a href="{{ route('team.show', $member) }}"
                                                           class="hover:text-blue-600">
                                                            {{ $member->name }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-600">{{ $member->email }}</p>
                                                </div>
                                            </div>
                                            <span
                                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($member->status === 'active') bg-green-100 text-green-800
                                                @elseif($member->status === 'vacation') bg-yellow-100 text-yellow-800
                                                @elseif($member->status === 'busy') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($member->status) }}
                                            </span>
                                        </div>

                                        <div class="space-y-2 mb-4">
                                            @if ($member->department)
                                                <p class="text-sm text-gray-700">
                                                    <span class="font-medium">Department:</span> {{ $member->department }}
                                                </p>
                                            @endif
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Role:</span>
                                                {{ ucfirst(str_replace('_', ' ', $member->role)) }}
                                            </p>
                                        </div>

                                        <div class="grid grid-cols-3 gap-4 mb-4">
                                            <div class="text-center">
                                                <div class="text-lg font-semibold text-gray-900">
                                                    {{ $member->assigned_tasks_count }}</div>
                                                <div class="text-xs text-gray-500">Assigned Tasks</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-lg font-semibold text-gray-900">
                                                    {{ $member->getCurrentWorkload() }}</div>
                                                <div class="text-xs text-gray-500">Active Tasks</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-lg font-semibold text-gray-900">
                                                    {{ $member->getCompletionRate() }}%</div>
                                                <div class="text-xs text-gray-500">Completion Rate</div>
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
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No team members yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding your first team member.</p>
                            <div class="mt-6">
                                <a href="{{ route('team.create') }}" class="btn btn-primary">
                                    Add Team Member
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
