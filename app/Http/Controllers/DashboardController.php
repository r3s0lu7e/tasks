<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TaskStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $statuses = TaskStatus::all();
        $completedStatus = $statuses->where('alias', 'completed')->first();
        $cancelledStatus = $statuses->where('alias', 'cancelled')->first();
        $activeStatuses = $statuses->whereNotIn('alias', ['completed', 'cancelled']);

        // Get active projects with optimized eager loading and counts
        if ($user->isAdmin()) {
            $projects = Project::with(['owner:id,name'])
                ->withCount(['tasks', 'members'])
                ->withCount(['tasks as completed_tasks_count' => function ($query) use ($completedStatus) {
                    if ($completedStatus) {
                        $query->where('task_status_id', $completedStatus->id);
                    }
                }])
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            // Get projects owned by user or user is a member of
            $projects = Project::with(['owner:id,name'])
                ->withCount(['tasks', 'members'])
                ->withCount(['tasks as completed_tasks_count' => function ($query) use ($completedStatus) {
                    if ($completedStatus) {
                        $query->where('task_status_id', $completedStatus->id);
                    }
                }])
                ->where('status', 'active')
                ->where(function ($query) use ($user) {
                    $query->where('owner_id', $user->id)
                        ->orWhereHas('members', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        });
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        // Calculate progress for each project without N+1
        $projects->each(function ($project) {
            $project->calculated_progress = $project->tasks_count > 0
                ? round(($project->completed_tasks_count / $project->tasks_count) * 100)
                : 0;
        });

        // Get all team members with task counts - Admin only
        $teamMembers = collect();
        if ($user->isAdmin()) {
            $teamMembers = User::where('id', '!=', $user->id)
                ->withCount(['assignedTasks as assigned_tasks_count' => function ($query) use ($activeStatuses) {
                    $query->whereIn('task_status_id', $activeStatuses->pluck('id'));
                }])
                ->orderBy('name')
                ->limit(6)
                ->get();
        }

        // Build base query for tasks based on user access
        $baseTaskQuery = $user->isAdmin()
            ? Task::query()
            : Task::whereHas('project', function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    });
            });

        // Get tasks due today with proper query
        $todayTasks = (clone $baseTaskQuery)
            ->with(['project:id,name,color', 'assignee:id,name', 'status'])
            ->whereDate('due_date', today())
            ->whereNotIn('task_status_id', [$completedStatus?->id, $cancelledStatus?->id])
            ->orderBy('due_date', 'asc')
            ->get();

        // Get overdue tasks with proper query
        $overdueTasks = (clone $baseTaskQuery)
            ->with(['project:id,name,color', 'assignee:id,name', 'status'])
            ->whereDate('due_date', '<', today())
            ->whereNotIn('task_status_id', [$completedStatus?->id, $cancelledStatus?->id])
            ->orderBy('due_date', 'asc')
            ->get();

        // Get recent activity tasks separately
        $recentActivity = (clone $baseTaskQuery)
            ->with(['project:id,name,color', 'assignee:id,name', 'creator:id,name', 'status', 'type'])
            ->select('id', 'title', 'task_status_id', 'task_type_id', 'priority', 'due_date', 'project_id', 'assignee_id', 'creator_id', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->limit(8)
            ->get();

        // Calculate statistics with optimized queries
        if ($user->isAdmin()) {
            // Use raw queries for better performance on counts
            $stats = [
                'total_projects' => Project::count(),
                'total_tasks' => Task::count(),
                'completed_tasks' => $completedStatus ? Task::where('task_status_id', $completedStatus->id)->count() : 0,
                'overdue_tasks' => $overdueTasks->count(),
                'today_tasks' => $todayTasks->count(),
                'team_members' => User::where('id', '!=', $user->id)->count(),
                'active_team_members' => User::where('id', '!=', $user->id)->where('status', 'active')->count(),
            ];
        } else {
            // For non-admin users, use a single aggregated query
            $userProjectIds = Project::where('owner_id', $user->id)
                ->orWhereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->pluck('id');

            $taskStats = Task::whereIn('project_id', $userProjectIds)
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN task_status_id = ? THEN 1 ELSE 0 END) as completed
                ', [$completedStatus ? $completedStatus->id : -1])
                ->first();

            $stats = [
                'total_projects' => $userProjectIds->count(),
                'total_tasks' => $taskStats->total ?? 0,
                'completed_tasks' => $taskStats->completed ?? 0,
                'overdue_tasks' => $overdueTasks->count(),
                'today_tasks' => $todayTasks->count(),
            ];
        }

        // Team workload data with optimized queries - Admin only
        $teamWorkload = collect();
        if ($user->isAdmin()) {
            // Get all team members with aggregated task data in a single query
            $teamWorkload = User::where('id', '!=', $user->id)
                ->select('id', 'name', 'status')
                ->withCount([
                    'assignedTasks as total_tasks',
                    'assignedTasks as active_tasks' => function ($query) use ($activeStatuses) {
                        $query->whereIn('task_status_id', $activeStatuses->pluck('id'));
                    },
                    'assignedTasks as completed_tasks' => function ($query) use ($completedStatus) {
                        if ($completedStatus) {
                            $query->where('task_status_id', $completedStatus->id);
                        }
                    },
                    'assignedTasks as overdue_tasks' => function ($query) use ($completedStatus, $cancelledStatus) {
                        $query->whereDate('due_date', '<', today())
                            ->whereNotIn('task_status_id', [$completedStatus?->id, $cancelledStatus?->id]);
                    }
                ])
                ->get()
                ->map(function ($member) {
                    return [
                        'name' => $member->name,
                        'initials' => $this->getInitials($member->name),
                        'workload' => $member->active_tasks,
                        'completion_rate' => $member->total_tasks > 0
                            ? round(($member->completed_tasks / $member->total_tasks) * 100)
                            : 0,
                        'overdue_count' => $member->overdue_tasks,
                        'status' => $member->status,
                        'status_color' => $member->status_color,
                        'status_color_rgb' => $member->status_color_rgb,
                    ];
                });
        }

        return view('dashboard', compact(
            'projects',
            'teamMembers',
            'overdueTasks',
            'todayTasks',
            'stats',
            'teamWorkload',
            'recentActivity'
        ));
    }

    /**
     * Get initials from name without database query
     */
    private function getInitials($name)
    {
        $nameParts = explode(' ', trim($name));
        if (count($nameParts) >= 2) {
            return mb_strtoupper(mb_substr($nameParts[0], 0, 1) . mb_substr($nameParts[1], 0, 1));
        }
        return mb_strtoupper(mb_substr($name, 0, 1));
    }
}
