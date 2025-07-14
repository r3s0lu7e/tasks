@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Edit Task Type</h1>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <form action="{{ route('task-types.update', $type) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" name="name" id="name" value="{{ $type->name }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label for="color"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300">Color</label>
                        <input type="color" name="color" id="color" value="{{ $type->color }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label for="icon"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300">Icon</label>
                        <input type="text" name="icon" id="icon" value="{{ $type->icon }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Provide a Font Awesome class (e.g., 'fa-bug', 'fa-tasks').
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <a href="{{ route('task-types.index') }}" class="text-gray-600 dark:text-gray-400 mr-4">Cancel</a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-jira-blue text-white rounded-md hover:bg-blue-700">Update
                        Type</button>
                </div>
            </form>
        </div>
    </div>
@endsection
