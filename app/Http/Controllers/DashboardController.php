<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get active projects only
        $projects = Project::with(['tasks', 'members'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get all team members (excluding the main user)
        $teamMembers = User::where('id', '!=', $user->id)
            ->withCount(['assignedTasks'])
            ->orderBy('name')
            ->limit(6)
            ->get();

        // Get all tasks across all projects
        $allTasks = Task::with(['project', 'assignee'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get tasks due today (not overdue)
        $todayTasks = Task::whereDate('due_date', today())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['project', 'assignee'])
            ->orderBy('due_date', 'asc')
            ->get();

        // Get overdue tasks (due before today, excluding today)
        $overdueTasks = Task::whereDate('due_date', '<', today())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['project', 'assignee'])
            ->orderBy('due_date', 'asc')
            ->get();

        // Statistics
        $stats = [
            'total_projects' => Project::count(),
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'overdue_tasks' => $overdueTasks->count(),
            'today_tasks' => $todayTasks->count(),
            'team_members' => User::where('id', '!=', $user->id)->count(),
            'active_team_members' => User::where('id', '!=', $user->id)->where('status', 'active')->count(),
        ];

        // Team workload data
        $teamWorkload = User::where('id', '!=', $user->id)
            ->get()
            ->map(function ($member) {
                return [
                    'name' => $member->name,
                    'workload' => $member->getCurrentWorkload(),
                    'completion_rate' => $member->getCompletionRate(),
                    'overdue_count' => $member->getOverdueTasksCount(),
                    'status' => $member->status,
                ];
            });

        // Recent activity (tasks created, updated, completed)
        $recentActivity = Task::with(['project', 'assignee', 'creator'])
            ->orderBy('updated_at', 'desc')
            ->limit(8)
            ->get();

        return view('dashboard', compact(
            'projects',
            'teamMembers',
            'allTasks',
            'overdueTasks',
            'todayTasks',
            'stats',
            'teamWorkload',
            'recentActivity'
        ));
    }
}
