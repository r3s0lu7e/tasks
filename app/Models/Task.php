<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($task) {
            // Clean up removed images from description when task is updated
            $task->cleanupRemovedDescriptionImages();
        });

        static::deleting(function ($task) {
            // Delete task comments
            $task->comments()->delete();

            // Clean up images from description
            $task->cleanupDescriptionImages();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'project_id',
        'assignee_id',
        'creator_id',
        'due_date',
        'story_points',
        'estimated_hours',
        'actual_hours',
        'tags',
        'task_status_id',
        'task_type_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
        'tags' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the project that owns the task.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'task_status_id');
    }

    public function type()
    {
        return $this->belongsTo(TaskType::class, 'task_type_id');
    }

    /**
     * Get the user assigned to the task.
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    /**
     * Get the user who created the task.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the comments for the task.
     */
    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    /**
     * Get the attachments for the task.
     */
    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function checklistItems()
    {
        return $this->hasMany(ChecklistItem::class)->orderBy('order');
    }

    /**
     * The dependencies where this task is the blocker.
     */
    public function blockingDependencies()
    {
        return $this->hasMany(TaskDependency::class, 'task_id');
    }

    /**
     * The dependencies where this task is being blocked.
     */
    public function blockedByDependencies()
    {
        return $this->hasMany(TaskDependency::class, 'depends_on_task_id');
    }

    public function blockingTasks()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on_task_id')->where('type', 'blocks');
    }

    public function blockedByTasks()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'depends_on_task_id', 'task_id')->where('type', 'blocks');
    }


    /**
     * Check if the task is overdue.
     */
    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && $this->status->alias !== 'completed';
    }

    /**
     * Check if the task is completed.
     */
    public function isCompleted()
    {
        return $this->status->alias === 'completed';
    }

    /**
     * Get the task's priority color.
     */
    public function getPriorityColorAttribute()
    {
        return match ($this->priority) {
            'high' => 'red',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray',
        };
    }

    /**
     * Get the task's status color.
     */
    public function getStatusColorAttribute()
    {
        return $this->status->color;
    }

    /**
     * Scope to filter tasks by status.
     */
    public function scopeByStatus($query, $statusId)
    {
        return $query->where('task_status_id', $statusId);
    }

    /**
     * Scope to filter tasks by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope to filter tasks by assignee.
     */
    public function scopeByAssignee($query, $userId)
    {
        return $query->where('assignee_id', $userId);
    }

    /**
     * Scope to filter tasks by project.
     */
    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope to filter overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereHas('status', function ($q) {
                $q->where('alias', '!=', 'completed');
            });
    }

    /**
     * Scope to filter tasks due today.
     */
    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }

    /**
     * Clean up images removed from task description when task is updated
     */
    public function cleanupRemovedDescriptionImages()
    {
        // Get original description before update
        $originalDescription = $this->getOriginal('description') ?? '';
        $newDescription = $this->description ?? '';

        if (empty($originalDescription)) {
            return;
        }

        // Get image URLs from both descriptions
        $originalImageUrls = \App\Helpers\DescriptionHelper::extractImageUrls($originalDescription);
        $newImageUrls = \App\Helpers\DescriptionHelper::extractImageUrls($newDescription);

        // Find images that were removed (in original but not in new)
        $removedImageUrls = array_diff($originalImageUrls, $newImageUrls);

        foreach ($removedImageUrls as $imageUrl) {
            // Extract filename from URL
            $filename = basename(parse_url($imageUrl, PHP_URL_PATH));

            // Check if it's one of our uploaded images (starts with 'desc_')
            if (strpos($filename, 'desc_') === 0) {
                $path = 'task-images/' . $filename;

                // Delete from storage
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
    }

    /**
     * Clean up images from task description when task is deleted
     */
    public function cleanupDescriptionImages()
    {
        if (empty($this->description)) {
            return;
        }

        $imageUrls = \App\Helpers\DescriptionHelper::extractImageUrls($this->description);

        foreach ($imageUrls as $imageUrl) {
            // Extract filename from URL
            $filename = basename(parse_url($imageUrl, PHP_URL_PATH));

            // Check if it's one of our uploaded images (starts with 'desc_')
            if (strpos($filename, 'desc_') === 0) {
                $path = 'task-images/' . $filename;

                // Delete from storage
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
    }
}
