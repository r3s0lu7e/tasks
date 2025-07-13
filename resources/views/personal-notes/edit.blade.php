@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                                Edit Note
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Update your personal note</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('personal-notes.show', $personalNote) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                View Note
                            </a>
                            <a href="{{ route('personal-notes.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Back to Notes
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <form method="POST" action="{{ route('personal-notes.update', $personalNote) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title"
                                   value="{{ old('title', $personalNote->title) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('title') border-red-500 @enderror"
                                   placeholder="Enter note title...">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content" name="content" rows="8" required
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('content') border-red-500 @enderror"
                                      placeholder="Write your note content here...">{{ old('content', $personalNote->content) }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Color -->
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Color
                            </label>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($colorOptions as $colorValue => $colorName)
                                    <label class="flex items-center cursor-pointer color-option">
                                        <input type="radio" name="color" value="{{ $colorValue }}" class="sr-only"
                                               {{ old('color', $personalNote->color) === $colorValue ? 'checked' : '' }}>
                                        <div class="w-8 h-8 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center relative"
                                             style="background-color: {{ $colorValue }}">
                                            <div
                                                 class="w-4 h-4 rounded-full bg-white opacity-0 transition-opacity duration-200">
                                            </div>
                                        </div>
                                        <span
                                              class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $colorName }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('color')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tags
                            </label>
                            <input type="text" id="tags" name="tags"
                                   value="{{ old('tags', $personalNote->tags ? implode(', ', $personalNote->tags) : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('tags') border-red-500 @enderror"
                                   placeholder="Enter tags separated by commas (e.g., work, important, todo)">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Separate multiple tags with commas</p>
                            @error('tags')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Options -->
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="is_pinned" name="is_pinned" value="1"
                                       {{ old('is_pinned', $personalNote->is_pinned) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="is_pinned" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Pin this note
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="is_favorite" name="is_favorite" value="1"
                                       {{ old('is_favorite', $personalNote->is_favorite) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="is_favorite" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Add to favorites
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div
                             class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('personal-notes.show', $personalNote) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Update Note
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Color selection functionality
        document.querySelectorAll('input[name="color"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selection from all color options
                document.querySelectorAll('input[name="color"] + div > div').forEach(indicator => {
                    indicator.style.opacity = '0';
                });

                // Show selection for chosen color
                if (this.checked) {
                    this.nextElementSibling.querySelector('div').style.opacity = '1';
                }
            });

            // Set initial state
            if (radio.checked) {
                radio.nextElementSibling.querySelector('div').style.opacity = '1';
            }
        });
    </script>
@endsection
