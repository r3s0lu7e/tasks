<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Admin users can see all projects
        if ($user->isAdmin()) {
            $projects = Project::with(['tasks', 'members', 'owner'])->get();
        } else {
            // Get projects owned by user or user is a member of
            $ownedProjects = $user->ownedProjects()->with(['tasks', 'members', 'owner'])->get();
            $memberProjects = $user->projects()->with(['tasks', 'members', 'owner'])->get();
            $projects = $ownedProjects->merge($memberProjects)->unique('id');
        }

        // Add statistics to each project
        $projects = $projects->map(function ($project) {
            $project->total_tasks = $project->tasks->count();
            $project->completed_tasks = $project->tasks->where('status', 'completed')->count();
            $project->progress = $project->total_tasks > 0 ?
                round(($project->completed_tasks / $project->total_tasks) * 100, 1) : 0;
            return $project;
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
        // Check if user has access to this project
        $user = Auth::user();
        if (!$project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        $project->load(['tasks.assignee', 'tasks.creator', 'members', 'owner']);

        // Group tasks by status for kanban view
        $tasksByStatus = $project->tasks->groupBy('status');

        // Get project statistics
        $stats = [
            'total_tasks' => $project->tasks->count(),
            'completed_tasks' => $project->tasks->where('status', 'completed')->count(),
            'in_progress_tasks' => $project->tasks->where('status', 'in_progress')->count(),
            'todo_tasks' => $project->tasks->where('status', 'todo')->count(),
            'overdue_tasks' => $project->tasks->where('due_date', '<', now())
                ->whereNotIn('status', ['completed', 'cancelled'])->count(),
        ];

        return view('projects.show', compact('project', 'tasksByStatus', 'stats'));
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
        if (!$project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        $project->load(['members', 'owner']);

        // Get users who are not members yet
        $availableUsers = User::whereNotIn('id', $project->members->pluck('id'))
            ->where('id', '!=', $project->owner_id)
            ->get();

        return view('projects.members', compact('project', 'availableUsers'));
    }
}
