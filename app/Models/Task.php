<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($task) {
            // Delete task attachments from storage
            foreach ($task->attachments as $attachment) {
                if (\Storage::disk('public')->exists($attachment->path)) {
                    \Storage::disk('public')->delete($attachment->path);
                }
            }

            // Delete task attachments records
            $task->attachments()->delete();

            // Delete task comments
            $task->comments()->delete();
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
        'status',
        'priority',
        'type',
        'project_id',
        'assignee_id',
        'creator_id',
        'due_date',
        'story_points',
        'estimated_hours',
        'actual_hours',
        'tags',
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

    /**
     * Check if the task is overdue.
     */
    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'completed';
    }

    /**
     * Check if the task is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
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
        return match ($this->status) {
            'todo' => 'gray',
            'in_progress' => 'blue',
            'completed' => 'green',
            'blocked' => 'red',
            default => 'gray',
        };
    }

    /**
     * Scope to filter tasks by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
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
            ->where('status', '!=', 'completed');
    }

    /**
     * Scope to filter tasks due today.
     */
    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }
}
