<div class="relative note-card" style="border-left-color: {{ $note->color }}">

    <!-- Note Header -->
    <div class="p-4 pb-2">
        <div class="flex items-start justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate pr-2">
                {{ $note->title }}
            </h3>
            <div class="flex items-center space-x-1 flex-shrink-0">
                @if ($note->is_pinned)
                    <span class="text-yellow-500" title="Pinned">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                  clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
                @if ($note->is_favorite)
                    <span class="text-red-500" title="Favorite">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                  clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Note Content -->
    <div class="px-4 pb-4">
        <div class="text-gray-700 dark:text-gray-300 text-sm mb-3 line-clamp-4">
            {{ $note->truncated_content }}
        </div>

        <!-- Tags -->
        @if ($note->tags && count($note->tags) > 0)
            <div class="flex flex-wrap gap-1 mb-3">
                @foreach ($note->tags as $tag)
                    <span
                          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                        {{ $tag }}
                    </span>
                @endforeach
            </div>
        @endif

        <!-- Note Meta -->
        <div class="text-xs text-gray-500 dark:text-gray-400 mb-3">
            <div>Created: {{ $note->created_at->format('M j, Y') }}</div>
            @if ($note->updated_at != $note->created_at)
                <div>Updated: {{ $note->updated_at->format('M j, Y') }}</div>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <a href="{{ route('personal-notes.show', $note) }}"
                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                    View
                </a>
                <a href="{{ route('personal-notes.edit', $note) }}"
                   class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 text-sm font-medium">
                    Edit
                </a>
            </div>

            <div class="flex items-center space-x-1">
                <!-- Pin Toggle -->
                <button onclick="togglePin({{ $note->id }})"
                        class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ $note->is_pinned ? 'text-yellow-500' : 'text-gray-400' }}"
                        title="{{ $note->is_pinned ? 'Unpin' : 'Pin' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                </button>

                <!-- Favorite Toggle -->
                <button onclick="toggleFavorite({{ $note->id }})"
                        class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ $note->is_favorite ? 'text-red-500' : 'text-gray-400' }}"
                        title="{{ $note->is_favorite ? 'Remove from favorites' : 'Add to favorites' }}">
                    <svg class="w-4 h-4" fill="{{ $note->is_favorite ? 'currentColor' : 'none' }}"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </button>

                <!-- Delete -->
                <form method="POST" action="{{ route('personal-notes.destroy', $note) }}"
                      onsubmit="return confirm('Are you sure you want to delete this note?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-red-500 hover:text-red-700"
                            title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
