<?php

namespace App\Http\Controllers;

use App\Models\PersonalNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class PersonalNotesController extends Controller
{
    /**
     * Display a listing of the user's personal notes.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->personalNotes()->latest();

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Apply tag filter
        if ($request->filled('tag')) {
            $query->withTag($request->tag);
        }

        // Apply color filter
        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($sortBy === 'title') {
            $query->orderBy('title', $sortOrder);
        } elseif ($sortBy === 'updated_at') {
            $query->orderBy('updated_at', $sortOrder);
        } else {
            $query->orderBy('created_at', $sortOrder);
        }

        // If specific filters are applied, return filtered results normally
        if ($request->filled('pinned') || $request->filled('favorite')) {
            // Apply pinned filter
            if ($request->filled('pinned') && $request->pinned === '1') {
                $query->pinned();
            }

            // Apply favorite filter
            if ($request->filled('favorite') && $request->favorite === '1') {
                $query->favorite();
            }

            $notes = $query->paginate(12);
            $pinnedNotes = collect();
            $favoriteNotes = collect();
            $normalNotes = collect();
        } else {
            // For normal display, separate notes into three categories
            $allNotes = $query->get();

            $pinnedNotes = $allNotes->where('is_pinned', true);
            $favoriteNotes = $allNotes->where('is_favorite', true)->where('is_pinned', false);
            $normalNotes = $allNotes->where('is_pinned', false)->where('is_favorite', false);

            // Create empty paginated collection for compatibility
            $notes = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                12,
                1,
                ['path' => request()->url(), 'pageName' => 'page']
            );
        }

        // Get all unique tags for filter dropdown
        $allTags = PersonalNote::getTagsForUser($user->id);

        // Get color options
        $colorOptions = [
            '#fbbf24' => 'Yellow',
            '#f59e0b' => 'Orange',
            '#ef4444' => 'Red',
            '#10b981' => 'Green',
            '#3b82f6' => 'Blue',
            '#8b5cf6' => 'Purple',
            '#ec4899' => 'Pink',
            '#6b7280' => 'Gray',
        ];

        return view('personal-notes.index', compact('notes', 'pinnedNotes', 'favoriteNotes', 'normalNotes', 'allTags', 'colorOptions'));
    }

    /**
     * Show the form for creating a new personal note.
     */
    public function create()
    {
        $colorOptions = [
            '#fbbf24' => 'Yellow',
            '#f59e0b' => 'Orange',
            '#ef4444' => 'Red',
            '#10b981' => 'Green',
            '#3b82f6' => 'Blue',
            '#8b5cf6' => 'Purple',
            '#ec4899' => 'Pink',
            '#6b7280' => 'Gray',
        ];

        return view('personal-notes.create', compact('colorOptions'));
    }

    /**
     * Store a newly created personal note in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'color' => 'required|string|max:7',
            'tags' => 'nullable|string',
            'is_pinned' => 'boolean',
            'is_favorite' => 'boolean',
        ]);

        // Process tags
        $tags = null;
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $tags = array_filter($tags); // Remove empty tags
        }

        PersonalNote::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'color' => $request->color,
            'tags' => $tags,
            'is_pinned' => $request->boolean('is_pinned'),
            'is_favorite' => $request->boolean('is_favorite'),
        ]);

        return redirect()->route('personal-notes.index')
            ->with('success', 'Note created successfully.');
    }

    /**
     * Display the specified personal note.
     */
    public function show(PersonalNote $personalNote)
    {
        // Check if user owns this note
        if ($personalNote->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to view this note.');
        }

        return view('personal-notes.show', compact('personalNote'));
    }

    /**
     * Show the form for editing the specified personal note.
     */
    public function edit(PersonalNote $personalNote)
    {
        // Check if user owns this note
        if ($personalNote->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to edit this note.');
        }

        $colorOptions = [
            '#fbbf24' => 'Yellow',
            '#f59e0b' => 'Orange',
            '#ef4444' => 'Red',
            '#10b981' => 'Green',
            '#3b82f6' => 'Blue',
            '#8b5cf6' => 'Purple',
            '#ec4899' => 'Pink',
            '#6b7280' => 'Gray',
        ];

        return view('personal-notes.edit', compact('personalNote', 'colorOptions'));
    }

    /**
     * Update the specified personal note in storage.
     */
    public function update(Request $request, PersonalNote $personalNote)
    {
        // Check if user owns this note
        if ($personalNote->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to update this note.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'color' => 'required|string|max:7',
            'tags' => 'nullable|string',
            'is_pinned' => 'boolean',
            'is_favorite' => 'boolean',
        ]);

        // Process tags
        $tags = null;
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $tags = array_filter($tags); // Remove empty tags
        }

        $personalNote->update([
            'title' => $request->title,
            'content' => $request->content,
            'color' => $request->color,
            'tags' => $tags,
            'is_pinned' => $request->boolean('is_pinned'),
            'is_favorite' => $request->boolean('is_favorite'),
        ]);

        return redirect()->route('personal-notes.index')
            ->with('success', 'Note updated successfully.');
    }

    /**
     * Remove the specified personal note from storage.
     */
    public function destroy(PersonalNote $personalNote)
    {
        // Check if user owns this note
        if ($personalNote->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to delete this note.');
        }

        $personalNote->delete();

        return redirect()->route('personal-notes.index')
            ->with('success', 'Note deleted successfully.');
    }

    /**
     * Toggle the pinned status of a note.
     */
    public function togglePin(PersonalNote $personalNote)
    {
        // Check if user owns this note
        if ($personalNote->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to modify this note.');
        }

        $personalNote->togglePinned();

        return response()->json([
            'success' => true,
            'is_pinned' => $personalNote->is_pinned,
            'message' => $personalNote->is_pinned ? 'Note pinned successfully.' : 'Note unpinned successfully.'
        ]);
    }

    /**
     * Toggle the favorite status of a note.
     */
    public function toggleFavorite(PersonalNote $personalNote)
    {
        // Check if user owns this note
        if ($personalNote->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to modify this note.');
        }

        $personalNote->toggleFavorite();

        return response()->json([
            'success' => true,
            'is_favorite' => $personalNote->is_favorite,
            'message' => $personalNote->is_favorite ? 'Note added to favorites.' : 'Note removed from favorites.'
        ]);
    }

    /**
     * Get notes data for quick access widget.
     */
    public function quickAccess()
    {
        $user = Auth::user();

        $recentNotes = $user->personalNotes()->latest()->take(5)->get();
        $pinnedNotes = $user->personalNotes()->pinned()->latest()->take(3)->get();
        $favoriteNotes = $user->personalNotes()->favorite()->latest()->take(3)->get();

        return response()->json([
            'recent' => $recentNotes,
            'pinned' => $pinnedNotes,
            'favorites' => $favoriteNotes
        ]);
    }
}
