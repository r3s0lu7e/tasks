@extends('layouts.app')

@section('content')
    @vite('resources/js/icon-picker.js')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Create New Task Type</h1>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <form action="{{ route('task-types.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label for="color"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300">Color</label>
                        <input type="color" name="color" id="color" value="#808080" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label for="icon_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Icon
                            Color</label>
                        <input type="color" name="icon_color" id="icon_color" value="#FFFFFF" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div class="md:col-span-2">
                        <label for="icon"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300">Icon</label>
                        <div class="relative">
                            <input type="text" name="icon" id="icon" placeholder="e.g., fa-bug" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div id="icon-dropdown"
                                 class="hidden absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                <!-- Autocomplete results will be populated here -->
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Type to search or scroll through all available icons below.
                        </p>
                    </div>
                </div>

                <!-- Icon Grid Section -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Available Icons</h3>
                    <div class="mb-4">
                        <input type="text" id="icon-search" placeholder="Search icons..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div id="icon-grid"
                         class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-2 max-h-96 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-md p-4">
                        <!-- Icons will be populated here -->
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <a href="{{ route('task-types.index') }}" class="text-gray-600 dark:text-gray-400 mr-4">Cancel</a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-jira-blue text-white rounded-md hover:bg-blue-700">Create
                        Type</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.initIconPicker({
                iconInputId: 'icon',
                iconDropdownId: 'icon-dropdown',
                iconGridId: 'icon-grid',
                iconSearchId: 'icon-search',
                iconColorInputId: 'icon_color',
                backgroundColorInputId: 'color'
            });
        });
    </script>
@endsection
