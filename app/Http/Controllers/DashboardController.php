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

        // Get active projects based on user access
        if ($user->isAdmin()) {
            $projects = Project::with(['tasks', 'members'])
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            // Get projects owned by user or user is a member of
            $projects = Project::with(['tasks', 'members'])
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

        // Get all team members (excluding the main user) - Admin only
        $teamMembers = collect();
        if ($user->isAdmin()) {
            $teamMembers = User::where('id', '!=', $user->id)
                ->withCount(['assignedTasks'])
                ->orderBy('name')
                ->limit(6)
                ->get();
        }

        // Get tasks based on user access
        if ($user->isAdmin()) {
            $allTasks = Task::with(['project', 'assignee'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } else {
            // Get tasks from projects user has access to
            $allTasks = Task::whereHas('project', function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    });
            })->with(['project', 'assignee'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        // Get tasks due today based on user access
        if ($user->isAdmin()) {
            $todayTasks = Task::whereDate('due_date', today())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->with(['project', 'assignee'])
                ->orderBy('due_date', 'asc')
                ->get();
        } else {
            $todayTasks = Task::whereHas('project', function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    });
            })->whereDate('due_date', today())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->with(['project', 'assignee'])
                ->orderBy('due_date', 'asc')
                ->get();
        }

        // Get overdue tasks based on user access
        if ($user->isAdmin()) {
            $overdueTasks = Task::whereDate('due_date', '<', today())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->with(['project', 'assignee'])
                ->orderBy('due_date', 'asc')
                ->get();
        } else {
            $overdueTasks = Task::whereHas('project', function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    });
            })->whereDate('due_date', '<', today())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->with(['project', 'assignee'])
                ->orderBy('due_date', 'asc')
                ->get();
        }

        // Statistics based on user access
        if ($user->isAdmin()) {
            $stats = [
                'total_projects' => Project::count(),
                'total_tasks' => Task::count(),
                'completed_tasks' => Task::where('status', 'completed')->count(),
                'overdue_tasks' => $overdueTasks->count(),
                'today_tasks' => $todayTasks->count(),
            ];
        } else {
            // Count only projects and tasks user has access to
            $userProjectsCount = Project::where('owner_id', $user->id)
                ->orWhereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->count();

            $userTasksCount = Task::whereHas('project', function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    });
            })->count();

            $userCompletedTasksCount = Task::whereHas('project', function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    });
            })->where('status', 'completed')->count();

            $stats = [
                'total_projects' => $userProjectsCount,
                'total_tasks' => $userTasksCount,
                'completed_tasks' => $userCompletedTasksCount,
                'overdue_tasks' => $overdueTasks->count(),
                'today_tasks' => $todayTasks->count(),
            ];
        }

        // Add team statistics for admin users only
        if ($user->isAdmin()) {
            $stats['team_members'] = User::where('id', '!=', $user->id)->count();
            $stats['active_team_members'] = User::where('id', '!=', $user->id)->where('status', 'active')->count();
        }

        // Team workload data - Admin only
        $teamWorkload = collect();
        if ($user->isAdmin()) {
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
        }

        // Recent activity based on user access
        if ($user->isAdmin()) {
            $recentActivity = Task::with(['project', 'assignee', 'creator'])
                ->orderBy('updated_at', 'desc')
                ->limit(8)
                ->get();
        } else {
            $recentActivity = Task::whereHas('project', function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    });
            })->with(['project', 'assignee', 'creator'])
                ->orderBy('updated_at', 'desc')
                ->limit(8)
                ->get();
        }

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
