<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TaskStatus;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cache status IDs to avoid repeated queries
        $statusIds = TaskStatus::pluck('id', 'alias');
        $completedStatusId = $statusIds->get('completed');
        $cancelledStatusId = $statusIds->get('cancelled');
        $activeStatusIds = $statusIds->except(['completed', 'cancelled'])->values();

        // Get active projects with optimized counts using raw queries
        if ($user->isAdmin()) {
            $projects = DB::table('projects')
                ->select([
                    'projects.id',
                    'projects.name',
                    'projects.color',
                    'projects.status',
                    'projects.created_at',
                    'users.name as owner_name',
                    DB::raw('(SELECT COUNT(*) FROM tasks WHERE project_id = projects.id) as tasks_count'),
                    DB::raw('(SELECT COUNT(*) FROM project_members WHERE project_id = projects.id) as members_count'),
                    DB::raw("(SELECT COUNT(*) FROM tasks WHERE project_id = projects.id AND task_status_id = {$completedStatusId}) as completed_tasks_count")
                ])
                ->join('users', 'projects.owner_id', '=', 'users.id')
                ->where('projects.status', 'active')
                ->orderBy('projects.created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($project) {
                    $project->calculated_progress = $project->tasks_count > 0
                        ? round(($project->completed_tasks_count / $project->tasks_count) * 100)
                        : 0;
                    return $project;
                });
        } else {
            // For non-admin users, get projects they own or are members of
            $userProjectIds = DB::table('projects')
                ->select('projects.id')
                ->where('owner_id', $user->id)
                ->orWhereExists(function ($query) use ($user) {
                    $query->select(DB::raw(1))
                        ->from('project_members')
                        ->whereColumn('project_members.project_id', 'projects.id')
                        ->where('project_members.user_id', $user->id);
                })
                ->pluck('id');

            $projects = DB::table('projects')
                ->select([
                    'projects.id',
                    'projects.name',
                    'projects.color',
                    'projects.status',
                    'projects.created_at',
                    'users.name as owner_name',
                    DB::raw('(SELECT COUNT(*) FROM tasks WHERE project_id = projects.id) as tasks_count'),
                    DB::raw('(SELECT COUNT(*) FROM project_members WHERE project_id = projects.id) as members_count'),
                    DB::raw("(SELECT COUNT(*) FROM tasks WHERE project_id = projects.id AND task_status_id = {$completedStatusId}) as completed_tasks_count")
                ])
                ->join('users', 'projects.owner_id', '=', 'users.id')
                ->whereIn('projects.id', $userProjectIds)
                ->where('projects.status', 'active')
                ->orderBy('projects.created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($project) {
                    $project->calculated_progress = $project->tasks_count > 0
                        ? round(($project->completed_tasks_count / $project->tasks_count) * 100)
                        : 0;
                    return $project;
                });
        }

        // Get team members with task counts using raw queries - Admin only
        $teamMembers = collect();
        if ($user->isAdmin()) {
            $activeStatusIdsList = $activeStatusIds->implode(',');
            $teamMembers = DB::table('users')
                ->select([
                    'users.id',
                    'users.name',
                    'users.status',
                    DB::raw("(SELECT COUNT(*) FROM tasks WHERE assignee_id = users.id AND task_status_id IN ({$activeStatusIdsList})) as assigned_tasks_count")
                ])
                ->where('users.id', '!=', $user->id)
                ->orderBy('users.name')
                ->limit(6)
                ->get();
        }

        // Get task counts using optimized raw queries
        if ($user->isAdmin()) {
            $todayTasksQuery = "SELECT COUNT(*) FROM tasks WHERE DATE(due_date) = CURDATE() AND task_status_id NOT IN ({$completedStatusId}, {$cancelledStatusId})";
            $overdueTasksQuery = "SELECT COUNT(*) FROM tasks WHERE DATE(due_date) < CURDATE() AND task_status_id NOT IN ({$completedStatusId}, {$cancelledStatusId})";

            $todayTasksCount = DB::select("SELECT ({$todayTasksQuery}) as count")[0]->count;
            $overdueTasksCount = DB::select("SELECT ({$overdueTasksQuery}) as count")[0]->count;
        } else {
            // For non-admin users, limit to their projects
            $userProjectIdsList = $userProjectIds->implode(',');
            $todayTasksQuery = "SELECT COUNT(*) FROM tasks WHERE project_id IN ({$userProjectIdsList}) AND DATE(due_date) = CURDATE() AND task_status_id NOT IN ({$completedStatusId}, {$cancelledStatusId})";
            $overdueTasksQuery = "SELECT COUNT(*) FROM tasks WHERE project_id IN ({$userProjectIdsList}) AND DATE(due_date) < CURDATE() AND task_status_id NOT IN ({$completedStatusId}, {$cancelledStatusId})";

            $todayTasksCount = DB::select("SELECT ({$todayTasksQuery}) as count")[0]->count;
            $overdueTasksCount = DB::select("SELECT ({$overdueTasksQuery}) as count")[0]->count;
        }

        // Get actual task records for display (limited to recent ones)
        $baseTaskQuery = $user->isAdmin()
            ? Task::query()
            : Task::whereIn('project_id', $userProjectIds ?? []);

        $todayTasks = (clone $baseTaskQuery)
            ->with(['project:id,name,color', 'assignee:id,name', 'status:id,name'])
            ->whereDate('due_date', today())
            ->whereNotIn('task_status_id', [$completedStatusId, $cancelledStatusId])
            ->orderBy('due_date', 'asc')
            ->limit(10) // Limit to 10 most urgent
            ->get();

        $overdueTasks = (clone $baseTaskQuery)
            ->with(['project:id,name,color', 'assignee:id,name', 'status:id,name'])
            ->whereDate('due_date', '<', today())
            ->whereNotIn('task_status_id', [$completedStatusId, $cancelledStatusId])
            ->orderBy('due_date', 'asc')
            ->limit(10) // Limit to 10 most overdue
            ->get();

        $recentActivity = (clone $baseTaskQuery)
            ->with(['project:id,name,color', 'assignee:id,name', 'creator:id,name', 'status:id,name', 'type:id,name'])
            ->select('id', 'title', 'task_status_id', 'task_type_id', 'priority', 'due_date', 'project_id', 'assignee_id', 'creator_id', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->limit(8)
            ->get();

        // Calculate statistics with single optimized queries
        if ($user->isAdmin()) {
            $stats = DB::select("
                SELECT 
                    (SELECT COUNT(*) FROM projects) as total_projects,
                    (SELECT COUNT(*) FROM tasks) as total_tasks,
                    (SELECT COUNT(*) FROM tasks WHERE task_status_id = {$completedStatusId}) as completed_tasks,
                    (SELECT COUNT(*) FROM users WHERE id != {$user->id}) as team_members,
                    (SELECT COUNT(*) FROM users WHERE id != {$user->id} AND status = 'active') as active_team_members
            ")[0];

            $statsArray = [
                'total_projects' => $stats->total_projects,
                'total_tasks' => $stats->total_tasks,
                'completed_tasks' => $stats->completed_tasks,
                'overdue_tasks' => $overdueTasksCount,
                'today_tasks' => $todayTasksCount,
                'team_members' => $stats->team_members,
                'active_team_members' => $stats->active_team_members,
            ];
        } else {
            $userProjectIdsList = $userProjectIds->implode(',');
            $stats = DB::select("
                SELECT 
                    (SELECT COUNT(*) FROM projects WHERE id IN ({$userProjectIdsList})) as total_projects,
                    (SELECT COUNT(*) FROM tasks WHERE project_id IN ({$userProjectIdsList})) as total_tasks,
                    (SELECT COUNT(*) FROM tasks WHERE project_id IN ({$userProjectIdsList}) AND task_status_id = {$completedStatusId}) as completed_tasks
            ")[0];

            $statsArray = [
                'total_projects' => $stats->total_projects,
                'total_tasks' => $stats->total_tasks,
                'completed_tasks' => $stats->completed_tasks,
                'overdue_tasks' => $overdueTasksCount,
                'today_tasks' => $todayTasksCount,
            ];
        }

        // Team workload data with single optimized query - Admin only
        $teamWorkload = collect();
        if ($user->isAdmin()) {
            $activeStatusIdsList = $activeStatusIds->implode(',');
            $teamWorkloadData = DB::select("
                SELECT 
                    u.id,
                    u.name,
                    u.status,
                    (SELECT COUNT(*) FROM tasks WHERE assignee_id = u.id) as total_tasks,
                    (SELECT COUNT(*) FROM tasks WHERE assignee_id = u.id AND task_status_id IN ({$activeStatusIdsList})) as active_tasks,
                    (SELECT COUNT(*) FROM tasks WHERE assignee_id = u.id AND task_status_id = {$completedStatusId}) as completed_tasks,
                    (SELECT COUNT(*) FROM tasks WHERE assignee_id = u.id AND DATE(due_date) < CURDATE() AND task_status_id NOT IN ({$completedStatusId}, {$cancelledStatusId})) as overdue_tasks
                FROM users u 
                WHERE u.id != {$user->id}
                ORDER BY u.name
            ");

            $teamWorkload = collect($teamWorkloadData)->map(function ($member) {
                return [
                    'name' => $member->name,
                    'initials' => $this->getInitials($member->name),
                    'workload' => $member->active_tasks,
                    'completion_rate' => $member->total_tasks > 0
                        ? round(($member->completed_tasks / $member->total_tasks) * 100)
                        : 0,
                    'overdue_count' => $member->overdue_tasks,
                    'status' => $member->status,
                    'status_color' => $this->getStatusColor($member->status),
                    'status_color_rgb' => $this->getStatusColorRgb($member->status),
                ];
            });
        }

        return view('dashboard', compact(
            'projects',
            'teamMembers',
            'overdueTasks',
            'todayTasks',
            'teamWorkload',
            'recentActivity'
        ))->with('stats', $statsArray);
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

    /**
     * Get status color without model instantiation
     */
    private function getStatusColor($status)
    {
        return match ($status) {
            'active' => '#22C55E',
            'inactive' => '#6B7280',
            'vacation' => '#F59E0B',
            'busy' => '#EF4444',
            default => '#6B7280',
        };
    }

    /**
     * Get status color RGB without model instantiation
     */
    private function getStatusColorRgb($status)
    {
        $hex = ltrim($this->getStatusColor($status), '#');
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
