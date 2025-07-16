<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\TaskStatus;
use App\Models\TaskType;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Cache the completed status to avoid repeated queries
        $completedStatus = TaskStatus::where('alias', 'completed')->first();
        $completedStatusId = $completedStatus ? $completedStatus->id : null;

        // Admin users can see all projects
        if ($user->isAdmin()) {
            $projects = Project::with(['owner:id,name'])
                ->withCount([
                    'tasks',
                    'members',
                    'tasks as completed_tasks_count' => function ($query) use ($completedStatusId) {
                        if ($completedStatusId) {
                            $query->where('task_status_id', $completedStatusId);
                        }
                    }
                ])
                ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
                ->orderBy('name')
                ->get();
        } else {
            // Get projects owned by user or user is a member of
            $projects = Project::with(['owner:id,name'])
                ->withCount([
                    'tasks',
                    'members',
                    'tasks as completed_tasks_count' => function ($query) use ($completedStatusId) {
                        if ($completedStatusId) {
                            $query->where('task_status_id', $completedStatusId);
                        }
                    }
                ])
                ->where(function ($query) use ($user) {
                    $query->where('owner_id', $user->id)
                        ->orWhereHas('members', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        });
                })
                ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
                ->orderBy('name')
                ->get()
                ->unique('id'); // Remove duplicates in case user is both owner and member
        }

        // Calculate progress efficiently using the count data
        $projects->each(function ($project) {
            $project->total_tasks = $project->tasks_count;
            $project->completed_tasks = $project->completed_tasks_count ?? 0;
            $project->progress = $project->total_tasks > 0 ?
                round(($project->completed_tasks / $project->total_tasks) * 100, 1) : 0;
        });

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'key' => 'required|string|max:10|unique:projects,key',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,critical',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'color' => 'nullable|string|max:7',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'key' => strtoupper($request->key),
            'status' => $request->status,
            'priority' => $request->priority,
            'owner_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'color' => $request->color ?? '#3B82F6',
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        if (!Auth::user()->isAdmin()) {
            Gate::authorize('view', $project);
        }

        $project->load(['tasks.assignee', 'tasks.creator', 'tasks.status', 'tasks.type', 'members', 'owner']);

        $statuses = TaskStatus::orderBy('order')->get();
        $types = TaskType::all();

        // Group tasks by status ID for kanban view
        $tasksByStatus = $project->tasks->groupBy('task_status_id');

        // Get project statistics
        $completedStatus = $statuses->where('alias', 'completed')->first();
        $cancelledStatus = $statuses->where('alias', 'cancelled')->first();

        $stats = [
            'total_tasks' => $project->tasks->count(),
            'completed_tasks' => $completedStatus ? $tasksByStatus->get($completedStatus->id, collect())->count() : 0,
            'overdue_tasks' => $project->tasks
                ->where('due_date', '<', now())
                ->where('task_status_id', '!=', $completedStatus ? $completedStatus->id : -1)
                ->where('task_status_id', '!=', $cancelledStatus ? $cancelledStatus->id : -1)
                ->count(),
            'due_today_tasks' => $project->tasks
                ->where('due_date', '>=', today()->startOfDay())
                ->where('due_date', '<=', today()->endOfDay())
                ->where('task_status_id', '!=', optional($completedStatus)->id)
                ->where('task_status_id', '!=', optional($cancelledStatus)->id)
                ->count(),
        ];

        return view('projects.show', compact('project', 'tasksByStatus', 'stats', 'statuses', 'types'));
    }

    public function gantt(Project $project)
    {
        if (!Auth::user()->isAdmin()) {
            Gate::authorize('view', $project);
        }

        $tasks = $project->tasks()->with(['status', 'dependencies'])->get();

        $formattedTasks = $tasks->map(function ($task) {
            $startDate = $task->start_date;
            $dueDate = $task->due_date;

            if (!$dueDate) {
                return null;
            }

            if (!$startDate) {
                $startDate = $dueDate;
            }

            return [
                'id' => 'Task_' . $task->id,
                'name' => $task->title,
                'start' => $startDate->format('Y-m-d'),
                'end' => $dueDate->format('Y-m-d'),
                'progress' => $task->progress ?? 0,
                'dependencies' => $task->dependencies->pluck('depends_on_task_id')->map(function ($id) {
                    return 'Task_' . $id;
                })->implode(','),
                'custom_class' => strtolower(str_replace(' ', '-', optional($task->status)->name ?? ''))
            ];
        })->filter()->values();

        return view('projects.gantt', [
            'project' => $project,
            'tasks' => json_encode($formattedTasks)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        // Check if user is owner or admin
        $user = Auth::user();
        if ($project->owner_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'You do not have permission to edit this project.');
        }

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        // Check if user is owner or admin
        $user = Auth::user();
        if ($project->owner_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'You do not have permission to edit this project.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'key' => ['required', 'string', 'max:10', Rule::unique('projects', 'key')->ignore($project->id)],
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,critical',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'color' => 'nullable|string|max:7',
        ]);

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'key' => strtoupper($request->key),
            'status' => $request->status,
            'priority' => $request->priority,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'color' => $request->color ?? '#3B82F6',
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // Check if user is owner or admin
        $user = Auth::user();
        if ($project->owner_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'You do not have permission to delete this project.');
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    /**
     * Add a member to the project.
     */
    public function addMember(Request $request, Project $project)
    {
        // Check if user is owner or admin
        $user = Auth::user();
        if ($project->owner_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'You do not have permission to manage project members.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $memberUser = User::find($request->user_id);

        if ($project->hasMember($memberUser)) {
            return back()->with('error', 'User is already a member of this project.');
        }

        $project->members()->attach($memberUser->id);

        return back()->with('success', 'Member added successfully.');
    }

    /**
     * Remove a member from the project.
     */
    public function removeMember(Project $project, User $user)
    {
        // Check if user is owner or admin
        $currentUser = Auth::user();
        if ($project->owner_id !== $currentUser->id && !$currentUser->isAdmin()) {
            abort(403, 'You do not have permission to manage project members.');
        }

        // Can't remove the owner
        if ($project->owner_id === $user->id) {
            return back()->with('error', 'Cannot remove the project owner.');
        }

        $project->members()->detach($user->id);

        return back()->with('success', 'Member removed successfully.');
    }

    /**
     * Show project members management page.
     */
    public function members(Project $project)
    {
        // Check if user has access to this project
        $user = Auth::user();
        if (!$user->isAdmin() && !$project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        $project->load(['members', 'owner']);

        // Get users who are not members yet
        $availableUsers = User::whereNotIn('id', $project->members->pluck('id'))
            ->where('id', '!=', $project->owner_id)
            ->get();

        return view('projects.members', compact('project', 'availableUsers'));
    }

    /**
     * Get project members for API calls.
     */
    public function getProjectMembers(Project $project)
    {
        // Check if user has access to this project
        $user = Auth::user();
        if (!$user->isAdmin() && !$project->hasMember($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get project members (including owner)
        $members = collect([$project->owner])
            ->merge($project->members)
            ->unique('id')
            ->sortBy('name')
            ->values()
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'department' => $member->department,
                    'role' => $member->role,
                ];
            });

        return response()->json($members);
    }
}
