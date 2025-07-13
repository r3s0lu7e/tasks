@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                                Personal Notes
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Manage your personal scratchpad notes</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('personal-notes.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4v16m8-8H4"></path>
                                </svg>
                                New Note
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <form method="GET" action="{{ route('personal-notes.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                            <!-- Search -->
                            <div>
                                <label for="search"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                       placeholder="Search notes..."
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            </div>

                            <!-- Tag Filter -->
                            <div>
                                <label for="tag"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tag</label>
                                <select id="tag" name="tag"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">All tags</option>
                                    @foreach ($allTags as $tag)
                                        <option value="{{ $tag }}" {{ request('tag') === $tag ? 'selected' : '' }}>
                                            {{ $tag }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Color Filter -->
                            <div>
                                <label for="color"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Color</label>
                                <select id="color" name="color"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">All colors</option>
                                    @foreach ($colorOptions as $colorValue => $colorName)
                                        <option value="{{ $colorValue }}"
                                                {{ request('color') === $colorValue ? 'selected' : '' }}>{{ $colorName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pinned Filter -->
                            <div>
                                <label for="pinned"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pinned</label>
                                <select id="pinned" name="pinned"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">All notes</option>
                                    <option value="1" {{ request('pinned') === '1' ? 'selected' : '' }}>Pinned only
                                    </option>
                                </select>
                            </div>

                            <!-- Favorite Filter -->
                            <div>
                                <label for="favorite"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Favorites</label>
                                <select id="favorite" name="favorite"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">All notes</option>
                                    <option value="1" {{ request('favorite') === '1' ? 'selected' : '' }}>Favorites
                                        only</option>
                                </select>
                            </div>

                            <!-- Sort -->
                            <div>
                                <label for="sort_by"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort</label>
                                <select id="sort_by" name="sort_by"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>
                                        Created Date</option>
                                    <option value="updated_at" {{ request('sort_by') === 'updated_at' ? 'selected' : '' }}>
                                        Updated Date</option>
                                    <option value="title" {{ request('sort_by') === 'title' ? 'selected' : '' }}>Title
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Filter
                            </button>
                            <a href="{{ route('personal-notes.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Three Column Layout for Notes -->
            @if (!request()->filled('pinned') && !request()->filled('favorite'))
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Pinned Notes Column -->
                    <div class="space-y-4">
                        <div
                             class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                Pinned Notes
                                <span
                                      class="ml-2 text-sm text-gray-500 dark:text-gray-400">({{ $pinnedNotes->count() }})</span>
                            </h3>
                            <div class="space-y-3">
                                @forelse($pinnedNotes as $note)
                                    @include('personal-notes.partials.note-card', ['note' => $note])
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No pinned notes</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Favorite Notes Column -->
                    <div class="space-y-4">
                        <div
                             class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                          clip-rule="evenodd" />
                                </svg>
                                Favorite Notes
                                <span
                                      class="ml-2 text-sm text-gray-500 dark:text-gray-400">({{ $favoriteNotes->count() }})</span>
                            </h3>
                            <div class="space-y-3">
                                @forelse($favoriteNotes as $note)
                                    @include('personal-notes.partials.note-card', ['note' => $note])
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No favorite notes</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Normal Notes Column -->
                    <div class="space-y-4">
                        <div
                             class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Notes
                                <span
                                      class="ml-2 text-sm text-gray-500 dark:text-gray-400">({{ $normalNotes->count() }})</span>
                            </h3>
                            <div class="space-y-3">
                                @forelse($normalNotes as $note)
                                    @include('personal-notes.partials.note-card', ['note' => $note])
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No notes yet</p>
                                        <div class="mt-4">
                                            <a href="{{ route('personal-notes.create') }}"
                                               class="inline-flex items-center px-3 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Create Note
                                            </a>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Filtered Results (when using filters) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @forelse($notes as $note)
                        @include('personal-notes.partials.note-card', ['note' => $note])
                    @empty
                        <div class="col-span-full">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No notes found</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your filters or
                                    create a new note.</p>
                                <div class="mt-6">
                                    <a href="{{ route('personal-notes.create') }}"
                                       class="inline-flex items-center px-4 py-2 bg-jira-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Create Note
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination for filtered results -->
                @if ($notes->hasPages())
                    <div class="mt-6">
                        {{ $notes->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- JavaScript for AJAX actions -->
    <script>
        function togglePin(noteId) {
            fetch(`/personal-notes/${noteId}/toggle-pin`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }

        function toggleFavorite(noteId) {
            fetch(`/personal-notes/${noteId}/toggle-favorite`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }
    </script>
@endsection
