<?php

namespace App\Models;

use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($project) {
            // Delete all tasks and their related data
            foreach ($project->tasks as $task) {


                // Delete task comments
                $task->comments()->delete();

                // Delete the task
                $task->delete();
            }

            // Detach all project members
            $project->members()->detach();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'key',
        'status',
        'owner_id',
        'start_date',
        'end_date',
        'priority',
        'color',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the owner of the project.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the members of the project.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members');
    }

    /**
     * Get the tasks for the project.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the project's completed tasks.
     */
    public function completedTasks()
    {
        $completedStatus = TaskStatus::where('alias', 'completed')->first();
        if (!$completedStatus) {
            return $this->tasks()->whereRaw('1 = 0');
        }
        return $this->tasks()->where('task_status_id', $completedStatus->id);
    }

    /**
     * Get the project's pending tasks.
     */
    public function pendingTasks()
    {
        $completedStatus = TaskStatus::where('alias', 'completed')->first();
        $cancelledStatus = TaskStatus::where('alias', 'cancelled')->first();

        $query = $this->tasks();

        if ($completedStatus) {
            $query->where('task_status_id', '!=', $completedStatus->id);
        }
        if ($cancelledStatus) {
            $query->where('task_status_id', '!=', $cancelledStatus->id);
        }

        return $query;
    }

    /**
     * Check if user is a member of the project.
     */
    public function hasMember(User $user)
    {
        // Admin users have access to all projects
        if ($user->isAdmin()) {
            return true;
        }

        return $this->members()->where('user_id', $user->id)->exists() || $this->owner_id === $user->id;
    }

    /**
     * Get project progress percentage.
     */
    public function getProgressAttribute()
    {
        // Use count attributes if available (from withCount)
        if (isset($this->attributes['tasks_count'])) {
            $totalTasks = $this->tasks_count;
            $completedTasks = $this->completed_tasks_count ?? 0;
        } else {
            // Fallback to direct queries
            $totalTasks = $this->tasks()->count();
            $completedTasks = $this->completedTasks()->count();
        }

        if ($totalTasks === 0) {
            return 0;
        }

        return round(($completedTasks / $totalTasks) * 100);
    }

    /**
     * Scope to include task counts for efficient loading.
     */
    public function scopeWithTaskCounts($query)
    {
        $completedStatus = TaskStatus::where('alias', 'completed')->first();
        $completedStatusId = $completedStatus ? $completedStatus->id : null;

        return $query->withCount([
            'tasks',
            'members',
            'tasks as completed_tasks_count' => function ($query) use ($completedStatusId) {
                if ($completedStatusId) {
                    $query->where('task_status_id', $completedStatusId);
                }
            }
        ]);
    }

    /**
     * Scope to filter projects by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter projects by owner.
     */
    public function scopeByOwner($query, $userId)
    {
        return $query->where('owner_id', $userId);
    }

    /**
     * Scope to filter projects where user is member.
     */
    public function scopeWhereUserIsMember($query, $userId)
    {
        return $query->whereHas('members', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->orWhere('owner_id', $userId);
    }
}
