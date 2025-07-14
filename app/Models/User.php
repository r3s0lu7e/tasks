<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\TaskStatus;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'department',
        'phone',
        'status',
        'hourly_rate',
        'hire_date',
        'notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'hire_date' => 'date',
        'hourly_rate' => 'decimal:2',
    ];

    /**
     * Get the projects owned by the user.
     */
    public function ownedProjects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    /**
     * Get the projects the user is a member of.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_members');
    }

    /**
     * Get the tasks assigned to the user.
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }

    /**
     * Get the tasks created by the user.
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'creator_id');
    }

    /**
     * Get the task comments by the user.
     */
    public function taskComments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function savedFilters()
    {
        return $this->hasMany(SavedFilter::class);
    }

    /**
     * Get the period calendar entries for this user.
     */
    public function periodCalendar()
    {
        return $this->hasMany(PeriodCalendar::class);
    }

    /**
     * Get the personal notes for this user.
     */
    public function personalNotes()
    {
        return $this->hasMany(PersonalNote::class);
    }

    /**
     * Check if user is admin (the main manager).
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is project manager.
     */
    public function isProjectManager()
    {
        return $this->role === 'project_manager';
    }

    /**
     * Check if user can manage a project.
     */
    public function canManageProject(Project $project)
    {
        return $this->isAdmin() || $this->id === $project->owner_id;
    }

    /**
     * Get user's current workload (active tasks).
     */
    public function getCurrentWorkload()
    {
        $activeStatuses = TaskStatus::whereNotIn('alias', ['completed', 'cancelled'])->pluck('id');
        return $this->assignedTasks()
            ->whereIn('task_status_id', $activeStatuses)
            ->count();
    }

    /**
     * Get user's completion rate.
     */
    public function getCompletionRate()
    {
        $totalTasks = $this->assignedTasks()->count();
        if ($totalTasks === 0) return 0;

        $completedStatus = TaskStatus::where('alias', 'completed')->first();
        if (!$completedStatus) return 0;

        $completedTasks = $this->assignedTasks()
            ->where('task_status_id', $completedStatus->id)
            ->count();

        return round(($completedTasks / $totalTasks) * 100);
    }

    /**
     * Get user's overdue tasks count.
     */
    public function getOverdueTasksCount()
    {
        $completedStatus = TaskStatus::where('alias', 'completed')->first();
        $cancelledStatus = TaskStatus::where('alias', 'cancelled')->first();

        return $this->assignedTasks()
            ->whereDate('due_date', '<', today())
            ->whereNotIn('task_status_id', [$completedStatus?->id, $cancelledStatus?->id])
            ->count();
    }

    /**
     * Get user's initials for display.
     */
    public function getInitials()
    {
        $nameParts = explode(' ', trim($this->name));
        if (count($nameParts) >= 2) {
            return mb_strtoupper(mb_substr($nameParts[0], 0, 1) . mb_substr($nameParts[1], 0, 1));
        }
        // If only one name, return the first letter
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }

    /**
     * Scope to get only active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get team members by role.
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Get user's status color for UI.
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'active' => '#22C55E',
            'inactive' => '#6B7280',
            'vacation' => '#F59E0B',
            'busy' => '#EF4444',
            default => '#6B7280',
        };
    }

    public function getStatusColorRgbAttribute()
    {
        $hex = ltrim($this->getStatusColorAttribute(), '#');
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return "$r, $g, $b";
    }
}
