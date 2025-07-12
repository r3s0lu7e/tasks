<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        // Check if user has access to this project
        $user = Auth::user();
        if (!$project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        $project->load(['tasks.assignee', 'tasks.creator', 'members']);

        // Get tasks with filters
        $query = $project->tasks()->with(['assignee', 'creator']);

        // Apply filters
        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('assignee')) {
            $query->where('assignee_id', request('assignee'));
        }

        if (request('priority')) {
            $query->where('priority', request('priority'));
        }

        if (request('type')) {
            $query->where('type', request('type'));
        }

        if (request('search')) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%');
            });
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(20);

        // Group tasks by status for kanban view
        $tasksByStatus = $project->tasks->groupBy('status');

        return view('tasks.index', compact('project', 'tasks', 'tasksByStatus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        // Check if user has access to this project
        $user = Auth::user();
        if (!$project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        // Get all team members (users) for assignment
        $assignableUsers = User::orderBy('name')->get();

        return view('tasks.create', compact('project', 'assignableUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        // Check if user has access to this project
        $user = Auth::user();
        if (!$project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:story,bug,task,epic',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:todo,in_progress,completed,cancelled',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'story_points' => 'nullable|integer|min:1|max:100',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip,rar'
        ]);

        // Validate assignee exists (removed project member restriction)
        if ($request->assignee_id) {
            $assignee = User::find($request->assignee_id);
            if (!$assignee) {
                return back()->withErrors(['assignee_id' => 'Selected assignee does not exist.']);
            }
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'priority' => $request->priority,
            'status' => $request->status,
            'project_id' => $project->id,
            'creator_id' => Auth::id(),
            'assignee_id' => $request->assignee_id,
            'due_date' => $request->due_date,
            'story_points' => $request->story_points,
        ]);

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('attachments', $filename, 'public');

                $task->attachments()->create([
                    'filename' => $filename,
                    'original_filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => auth()->id()
                ]);
            }
        }

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Task $task)
    {
        // Check if user has access to this project
        $user = Auth::user();
        if (!$project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        // Ensure task belongs to the project
        if ($task->project_id !== $project->id) {
            abort(404, 'Task not found in this project.');
        }

        $task->load(['assignee', 'creator', 'comments.user']);

        // Get all team members (users) for assignment
        $assignableUsers = User::orderBy('name')->get();

        return view('tasks.show', compact('project', 'task', 'assignableUsers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, Task $task)
    {
        // Check if user has access to this project
        $user = Auth::user();
        if (!$project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        // Ensure task belongs to the project
        if ($task->project_id !== $project->id) {
            abort(404, 'Task not found in this project.');
        }

        // Get all team members (users) for assignment
        $assignableUsers = User::orderBy('name')->get();

        return view('tasks.edit', compact('project', 'task', 'assignableUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, Task $task)
    {
        // Check if user has access to this project
        $user = Auth::user();
        if (!$project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        // Ensure task belongs to the project
        if ($task->project_id !== $project->id) {
            abort(404, 'Task not found in this project.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:story,bug,task,epic',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:todo,in_progress,completed,cancelled',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'story_points' => 'nullable|integer|min:1|max:100',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip,rar'
        ]);

        // Validate assignee is a member of the project
        if ($request->assignee_id) {
            $assignee = User::find($request->assignee_id);
            if (!$project->hasMember($assignee)) {
                return back()->withErrors(['assignee_id' => 'Assignee must be a member of the project.']);
            }
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'priority' => $request->priority,
            'status' => $request->status,
            'assignee_id' => $request->assignee_id,
            'due_date' => $request->due_date,
            'story_points' => $request->story_points,
        ]);

        // Handle new file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('attachments', $filename, 'public');

                $task->attachments()->create([
                    'filename' => $filename,
                    'original_filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => auth()->id()
                ]);
            }
        }

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Task $task)
    {
        // Check if user has access to this project
        $user = Auth::user();
        if (!$project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        // Ensure task belongs to the project
        if ($task->project_id !== $project->id) {
            abort(404, 'Task not found in this project.');
        }

        // Only creator, assignee, project owner, or admin can delete
        if (
            $task->creator_id !== $user->id &&
            $task->assignee_id !== $user->id &&
            $project->owner_id !== $user->id &&
            !$user->isAdmin()
        ) {
            abort(403, 'You do not have permission to delete this task.');
        }

        $task->delete();

        return redirect()->route('projects.tasks.index', $project)
            ->with('success', 'Task deleted successfully.');
    }

    /**
     * Update task status.
     */
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,completed,cancelled',
        ]);

        // Check if user has access to this task's project
        $user = Auth::user();
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        $task->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully.',
            'task' => $task->load(['assignee', 'creator'])
        ]);
    }

    /**
     * Assign task to user.
     */
    public function assign(Request $request, Task $task)
    {
        $request->validate([
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        // Check if user has access to this task's project
        $user = Auth::user();
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        // Validate assignee is a member of the project
        if ($request->assignee_id) {
            $assignee = User::find($request->assignee_id);
            if (!$task->project->hasMember($assignee)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignee must be a member of the project.'
                ], 422);
            }
        }

        $task->update(['assignee_id' => $request->assignee_id]);

        return response()->json([
            'success' => true,
            'message' => 'Task assigned successfully.',
            'task' => $task->load(['assignee', 'creator'])
        ]);
    }

    /**
     * Get tasks for kanban board.
     */
    public function kanban(Project $project)
    {
        // Check if user has access to this project
        $user = Auth::user();
        if (!$project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        $tasks = $project->tasks()->with(['assignee', 'creator'])->get();
        $tasksByStatus = $tasks->groupBy('status');

        return response()->json([
            'tasks' => $tasksByStatus,
            'statuses' => ['todo', 'in_progress', 'completed', 'cancelled']
        ]);
    }

    public function deleteAttachment(Task $task, $attachmentId)
    {
        $user = Auth::user();
        $attachment = $task->attachments()->findOrFail($attachmentId);

        // Check if user has access to this task's project
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        // Only creator, assignee, project owner, attachment uploader, or admin can delete attachments
        if (
            $task->creator_id !== $user->id &&
            $task->assignee_id !== $user->id &&
            $task->project->owner_id !== $user->id &&
            $attachment->uploaded_by !== $user->id &&
            !$user->isAdmin()
        ) {
            abort(403, 'You do not have permission to delete this attachment.');
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($attachment->path)) {
            Storage::disk('public')->delete($attachment->path);
        }

        $attachment->delete();

        return back()->with('success', 'Attachment deleted successfully.');
    }

    public function downloadAttachment(Task $task, $attachmentId)
    {
        $attachment = $task->attachments()->findOrFail($attachmentId);

        if (Storage::disk('public')->exists($attachment->path)) {
            return Storage::disk('public')->download($attachment->path, $attachment->filename);
        }

        return back()->with('error', 'File not found.');
    }

    // Global task methods for single-user convenience

    /**
     * Display a listing of all tasks across projects.
     */
    public function globalIndex(Request $request)
    {
        $user = Auth::user();

        // Admin users can see all tasks
        if ($user->isAdmin()) {
            $query = Task::with(['project', 'assignee', 'creator']);
        } else {
            // Get all tasks from user's projects
            $query = Task::whereHas('project', function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    });
            })->with(['project', 'assignee', 'creator']);
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        if ($request->filled('assignee')) {
            if ($request->assignee === 'unassigned') {
                $query->whereNull('assignee_id');
            } else {
                $query->where('assignee_id', $request->assignee);
            }
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get projects for filter dropdown
        if ($user->isAdmin()) {
            $projects = Project::all();
        } else {
            $projects = Project::where('owner_id', $user->id)
                ->orWhereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->get();
        }

        // Get all users for assignee filter
        $users = User::orderBy('name')->get();

        return view('tasks.global-index', compact('tasks', 'projects', 'users'));
    }

    /**
     * Show the form for creating a new task (standalone).
     */
    public function createStandalone()
    {
        $user = Auth::user();

        // Get projects for admin users or user's projects
        if ($user->isAdmin()) {
            $projects = Project::all();
        } else {
            $projects = Project::where('owner_id', $user->id)
                ->orWhereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->get();
        }

        if ($projects->isEmpty()) {
            return redirect()->route('projects.create')
                ->with('error', 'You need to create a project first before creating tasks.');
        }

        // Use the first project as default, or let user choose
        $project = $projects->first();

        // Get all team members (users) for assignment
        $assignableUsers = User::orderBy('name')->get();

        return view('tasks.create-standalone', compact('projects', 'project', 'assignableUsers'));
    }

    /**
     * Store a newly created task (standalone).
     */
    public function storeStandalone(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:story,bug,task,epic',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:todo,in_progress,completed,cancelled',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'story_points' => 'nullable|integer|min:1|max:100',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip,rar'
        ]);

        // Verify user has access to the project
        $project = Project::findOrFail($request->project_id);
        if (!$project->hasMember($user)) {
            return back()->withErrors(['project_id' => 'You do not have access to this project.']);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'priority' => $request->priority,
            'status' => $request->status,
            'project_id' => $request->project_id,
            'creator_id' => $user->id,
            'assignee_id' => $request->assignee_id, // Can assign to any team member
            'due_date' => $request->due_date,
            'story_points' => $request->story_points,
        ]);

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('attachments', $filename, 'public');

                $task->attachments()->create([
                    'filename' => $filename,
                    'original_filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => $user->id
                ]);
            }
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task created successfully.');
    }

    /**
     * Show the form for editing the specified task (standalone).
     */
    public function editStandalone(Task $task)
    {
        $user = Auth::user();

        // Check if user has access to this task's project
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this task.');
        }

        // Get projects for admin users or user's projects
        if ($user->isAdmin()) {
            $projects = Project::all();
        } else {
            $projects = Project::where('owner_id', $user->id)
                ->orWhereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->get();
        }

        // Get all team members (users) for assignment
        $assignableUsers = User::orderBy('name')->get();

        // Use the existing edit view with project context
        $project = $task->project;

        return view('tasks.edit', compact('task', 'project', 'assignableUsers'));
    }

    /**
     * Update the specified task (standalone).
     */
    public function updateStandalone(Request $request, Task $task)
    {
        $user = Auth::user();

        // Check if user has access to this task's project
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this task.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:story,bug,task,epic',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:todo,in_progress,completed,cancelled',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'story_points' => 'nullable|integer|min:1|max:100',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip,rar'
        ]);

        // Verify user has access to the new project (if changed)
        if ($request->project_id != $task->project_id) {
            $newProject = Project::findOrFail($request->project_id);
            if (!$newProject->hasMember($user)) {
                return back()->withErrors(['project_id' => 'You do not have access to this project.']);
            }
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'priority' => $request->priority,
            'status' => $request->status,
            'project_id' => $request->project_id,
            'assignee_id' => $request->assignee_id,
            'due_date' => $request->due_date,
            'story_points' => $request->story_points,
        ]);

        // Handle new file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('attachments', $filename, 'public');

                $task->attachments()->create([
                    'filename' => $filename,
                    'original_filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => $user->id
                ]);
            }
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Display the specified task (global).
     */
    public function showGlobal(Task $task)
    {
        $user = Auth::user();

        // Check if user has access to this task's project
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this task.');
        }

        $task->load(['project', 'assignee', 'creator', 'comments.user']);

        // Get all team members (users) for assignment
        $assignableUsers = User::orderBy('name')->get();

        // Use the existing show view with project context
        $project = $task->project;

        return view('tasks.show', compact('task', 'project', 'assignableUsers'));
    }

    /**
     * Show the form for editing the specified task (global).
     */
    public function editGlobal(Task $task)
    {
        $user = Auth::user();

        // Check if user has access to this task's project
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this task.');
        }

        // Get projects for admin users or user's projects
        if ($user->isAdmin()) {
            $projects = Project::all();
        } else {
            $projects = Project::where('owner_id', $user->id)
                ->orWhereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->get();
        }

        // Get all team members (users) for assignment
        $assignableUsers = User::orderBy('name')->get();

        return view('tasks.global-edit', compact('task', 'projects', 'assignableUsers'));
    }

    /**
     * Update the specified task (global).
     */
    public function updateGlobal(Request $request, Task $task)
    {
        $user = Auth::user();

        // Check if user has access to this task's project
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this task.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:story,bug,task,epic',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:todo,in_progress,completed,cancelled',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'story_points' => 'nullable|integer|min:1|max:100',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip,rar'
        ]);

        // Verify user has access to the new project (if changed)
        if ($request->project_id != $task->project_id) {
            $newProject = Project::findOrFail($request->project_id);
            if (!$newProject->hasMember($user)) {
                return back()->withErrors(['project_id' => 'You do not have access to this project.']);
            }
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'priority' => $request->priority,
            'status' => $request->status,
            'project_id' => $request->project_id,
            'assignee_id' => $request->assignee_id,
            'due_date' => $request->due_date,
            'story_points' => $request->story_points,
        ]);

        // Handle new file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('attachments', $filename, 'public');

                $task->attachments()->create([
                    'filename' => $filename,
                    'original_filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => $user->id
                ]);
            }
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task (global).
     */
    public function destroyGlobal(Task $task)
    {
        $user = Auth::user();

        // Check if user has access to this task's project
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this task.');
        }

        // Delete attachments from storage
        foreach ($task->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->path)) {
                Storage::disk('public')->delete($attachment->path);
            }
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
