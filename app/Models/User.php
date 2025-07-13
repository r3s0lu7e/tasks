<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        return $this->assignedTasks()
            ->whereIn('status', ['todo', 'in_progress'])
            ->count();
    }

    /**
     * Get user's completion rate.
     */
    public function getCompletionRate()
    {
        $totalTasks = $this->assignedTasks()->count();
        if ($totalTasks === 0) return 0;

        $completedTasks = $this->assignedTasks()
            ->where('status', 'completed')
            ->count();

        return round(($completedTasks / $totalTasks) * 100);
    }

    /**
     * Get user's overdue tasks count.
     */
    public function getOverdueTasksCount()
    {
        return $this->assignedTasks()
            ->whereDate('due_date', '<', today())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
    }

    /**
     * Get user's initials for display.
     */
    public function getInitials()
    {
        $nameParts = explode(' ', trim($this->name));
        if (count($nameParts) >= 2) {
            return strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
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
            'active' => 'green',
            'inactive' => 'gray',
            'vacation' => 'yellow',
            'busy' => 'red',
            default => 'gray',
        };
    }
}
