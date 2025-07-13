<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalNote extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'color',
        'is_pinned',
        'is_favorite',
        'tags',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_pinned' => 'boolean',
        'is_favorite' => 'boolean',
        'tags' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include pinned notes.
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Scope a query to only include favorite notes.
     */
    public function scopeFavorite($query)
    {
        return $query->where('is_favorite', true);
    }

    /**
     * Scope a query to filter by tags.
     */
    public function scopeWithTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    /**
     * Scope a query to search notes by title and content.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('content', 'like', '%' . $search . '%');
        });
    }

    /**
     * Get the note's color with fallback.
     */
    public function getColorAttribute($value)
    {
        return $value ?: '#fbbf24'; // Default yellow color
    }

    /**
     * Get a truncated version of the content.
     */
    public function getTruncatedContentAttribute()
    {
        return strlen($this->content) > 150
            ? substr($this->content, 0, 150) . '...'
            : $this->content;
    }

    /**
     * Get all unique tags from user's notes.
     */
    public static function getTagsForUser($userId)
    {
        $notes = static::where('user_id', $userId)->get();
        $allTags = [];

        foreach ($notes as $note) {
            if ($note->tags) {
                $allTags = array_merge($allTags, $note->tags);
            }
        }

        return array_unique($allTags);
    }

    /**
     * Toggle the pinned status of the note.
     */
    public function togglePinned()
    {
        $this->update(['is_pinned' => !$this->is_pinned]);
        return $this;
    }

    /**
     * Toggle the favorite status of the note.
     */
    public function toggleFavorite()
    {
        $this->update(['is_favorite' => !$this->is_favorite]);
        return $this;
    }
}
