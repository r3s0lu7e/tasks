<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\TaskDependency;
use App\Models\TaskStatus;
use App\Models\TaskType;

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

        $project->load(['tasks.assignee', 'tasks.creator', 'tasks.status', 'tasks.type', 'members']);

        // Get tasks with filters
        $query = $project->tasks()->with(['assignee', 'creator', 'status', 'type']);

        // Apply filters
        if (request('status')) {
            $query->where('task_status_id', request('status'));
        }

        if (request('assignee')) {
            $query->where('assignee_id', request('assignee'));
        }

        if (request('priority')) {
            $query->where('priority', request('priority'));
        }

        if (request('type')) {
            $query->where('task_type_id', request('type'));
        }

        if (request('search')) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%');
            });
        }

        // Due date range filter
        if (request()->filled('due_date_range')) {
            $range = request('due_date_range');
            $completedStatus = TaskStatus::where('alias', 'completed')->first();
            $cancelledStatus = TaskStatus::where('alias', 'cancelled')->first();

            if ($range === 'today') {
                $query->whereDate('due_date', today());
            } elseif ($range === 'this_week') {
                $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($range === 'next_7_days') {
                $query->whereBetween('due_date', [today(), today()->addDays(7)]);
            } elseif ($range === 'this_month') {
                $query->whereMonth('due_date', now()->month);
            } elseif ($range === 'overdue') {
                $query->where('due_date', '<', today())
                    ->where('task_status_id', '!=', $completedStatus ? $completedStatus->id : -1)
                    ->where('task_status_id', '!=', $cancelledStatus ? $cancelledStatus->id : -1);
            } elseif ($range === 'no_due_date') {
                $query->whereNull('due_date');
            }
        }

        // Sorting
        $sortBy = request()->input('sort_by', 'created_at_desc');
        switch ($sortBy) {
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'due_date_asc':
                $query->orderByRaw('due_date IS NULL ASC, due_date ASC');
                break;
            case 'due_date_desc':
                $query->orderByRaw('due_date IS NULL ASC, due_date DESC');
                break;
            case 'priority_desc':
                $query->orderByRaw("CASE 
                    WHEN priority = 'critical' THEN 1 
                    WHEN priority = 'high' THEN 2 
                    WHEN priority = 'medium' THEN 3 
                    WHEN priority = 'low' THEN 4 
                    ELSE 5 END");
                break;
            case 'priority_asc':
                $query->orderByRaw("CASE 
                    WHEN priority = 'low' THEN 1 
                    WHEN priority = 'medium' THEN 2 
                    WHEN priority = 'high' THEN 3 
                    WHEN priority = 'critical' THEN 4 
                    ELSE 5 END");
                break;
            case 'updated_at_desc':
                $query->orderBy('updated_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }


        $tasks = $query->paginate(20);

        // Group tasks by status for kanban view
        $tasksByStatus = $project->tasks->groupBy('task_status_id');
        $statuses = TaskStatus::orderBy('order')->get();
        $types = TaskType::all();

        return view('tasks.index', compact('project', 'tasks', 'tasksByStatus', 'statuses', 'types'));
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

        $statuses = TaskStatus::orderBy('order')->get();
        $types = TaskType::all();

        // Get project members (including owner) for assignment
        $assignableUsers = collect([$project->owner])
            ->merge($project->members)
            ->unique('id')
            ->sortBy('name')
            ->values();

        return view('tasks.create', compact('project', 'assignableUsers', 'statuses', 'types'));
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
            'task_type_id' => 'required|exists:task_types,id',
            'priority' => 'required|in:low,medium,high,critical',
            'task_status_id' => 'required|exists:task_statuses,id',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'story_points' => 'nullable|integer|min:1|max:100',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip,rar'
        ]);

        // Validate assignee is a member of the project
        if ($request->assignee_id) {
            $assignee = User::find($request->assignee_id);
            if (!$assignee || !$project->hasMember($assignee)) {
                return back()->withErrors(['assignee_id' => 'Assignee must be a member of the project.']);
            }
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'task_type_id' => $request->task_type_id,
            'priority' => $request->priority,
            'task_status_id' => $request->task_status_id,
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

        $task->load(['assignee', 'creator', 'comments.user', 'status', 'type', 'blockingDependencies.dependentTask.project', 'blockedByDependencies.task.project']);

        // Get IDs of all related tasks
        $blockingIds = $task->blockingDependencies->pluck('depends_on_task_id');
        $blockedByIds = $task->blockedByDependencies->pluck('task_id');
        $existingDependencyIds = $blockingIds->merge($blockedByIds)->unique();


        // Get tasks for the dropdown
        $availableTasks = $project->tasks()
            ->where('id', '!=', $task->id)
            ->whereNotIn('id', $existingDependencyIds)
            ->get();

        // Get all team members (users) for assignment
        $assignableUsers = User::orderBy('name')->get();
        $statuses = TaskStatus::orderBy('order')->get();
        $types = TaskType::all();

        return view('tasks.show', compact('project', 'task', 'assignableUsers', 'availableTasks', 'statuses', 'types'));
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

        // Load attachments for displaying existing attachments
        $task->load('attachments');

        $statuses = TaskStatus::orderBy('order')->get();
        $types = TaskType::all();

        // Get project members (including owner) for assignment
        $assignableUsers = collect([$project->owner])
            ->merge($project->members)
            ->unique('id')
            ->sortBy('name')
            ->values();

        return view('tasks.edit', compact('project', 'task', 'assignableUsers', 'statuses', 'types'));
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
            'task_type_id' => 'required|exists:task_types,id',
            'priority' => 'required|in:low,medium,high,critical',
            'task_status_id' => 'required|exists:task_statuses,id',
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
            'task_type_id' => $request->task_type_id,
            'priority' => $request->priority,
            'task_status_id' => $request->task_status_id,
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
            'status_id' => 'sometimes|required|exists:task_statuses,id',
            'type_id' => 'sometimes|required|exists:task_types,id',
        ]);

        // Check if user has access to this task's project
        $user = Auth::user();
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        // Update status if provided
        if ($request->has('status_id')) {
            $task->update(['task_status_id' => $request->status_id]);
        }

        // Update type if provided
        if ($request->has('type_id')) {
            $task->update(['task_type_id' => $request->type_id]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'task' => $task->load(['assignee', 'creator', 'status', 'type'])
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
        $tasksByStatus = $tasks->groupBy('task_status_id');

        return response()->json([
            'tasks' => $tasksByStatus,
            'statuses' => ['todo', 'in_progress', 'completed', 'cancelled']
        ]);
    }

    public function deleteAttachment(Project $project, Task $task, TaskAttachment $attachment)
    {
        $user = Auth::user();

        // Check if user has access to this project
        if (!$project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        // Ensure task belongs to the project
        if ($task->project_id !== $project->id) {
            abort(404, 'Task not found in this project.');
        }

        // Ensure attachment belongs to this task
        if ($attachment->task_id !== $task->id) {
            abort(404, 'Attachment not found for this task.');
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

    public function downloadAttachment(Project $project, Task $task, TaskAttachment $attachment)
    {
        $user = Auth::user();

        // Check if user has access to this project
        if (!$project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        // Ensure task belongs to the project
        if ($task->project_id !== $project->id) {
            abort(404, 'Task not found in this project.');
        }

        // Ensure attachment belongs to this task
        if ($attachment->task_id !== $task->id) {
            abort(404, 'Attachment not found for this task.');
        }

        if (Storage::disk('public')->exists($attachment->path)) {
            return Storage::disk('public')->download($attachment->path, $attachment->filename);
        }

        return back()->with('error', 'File not found.');
    }

    // Standalone attachment methods (for backward compatibility)
    public function deleteAttachmentStandalone(Task $task, TaskAttachment $attachment)
    {
        $user = Auth::user();

        // Check if user has access to this task's project
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        // Ensure attachment belongs to this task
        if ($attachment->task_id !== $task->id) {
            abort(404, 'Attachment not found for this task.');
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

    public function downloadAttachmentStandalone(Task $task, TaskAttachment $attachment)
    {
        $user = Auth::user();

        // Check if user has access to this task's project
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        // Ensure attachment belongs to this task
        if ($attachment->task_id !== $task->id) {
            abort(404, 'Attachment not found for this task.');
        }

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
            $query = Task::with(['project', 'assignee', 'creator', 'status', 'type']);
        } else {
            // Get all tasks from user's projects
            $query = Task::whereHas('project', function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    });
            })->with(['project', 'assignee', 'creator', 'status', 'type']);
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('task_status_id', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('type')) {
            $query->where('task_type_id', $request->type);
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

        // Due date range filter
        if ($request->filled('due_date_range')) {
            $range = $request->due_date_range;
            $completedStatus = TaskStatus::where('alias', 'completed')->first();
            $cancelledStatus = TaskStatus::where('alias', 'cancelled')->first();

            if ($range === 'today') {
                $query->whereDate('due_date', today());
            } elseif ($range === 'this_week') {
                $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($range === 'next_7_days') {
                $query->whereBetween('due_date', [today(), today()->addDays(7)]);
            } elseif ($range === 'this_month') {
                $query->whereMonth('due_date', now()->month);
            } elseif ($range === 'overdue') {
                $query->where('due_date', '<', today())
                    ->where('task_status_id', '!=', $completedStatus ? $completedStatus->id : -1)
                    ->where('task_status_id', '!=', $cancelledStatus ? $cancelledStatus->id : -1);
            } elseif ($range === 'no_due_date') {
                $query->whereNull('due_date');
            }
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at_desc');
        switch ($sortBy) {
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'due_date_asc':
                $query->orderByRaw('due_date IS NULL ASC, due_date ASC');
                break;
            case 'due_date_desc':
                $query->orderByRaw('due_date IS NULL ASC, due_date DESC');
                break;
            case 'priority_desc':
                $query->orderByRaw("CASE 
                    WHEN priority = 'critical' THEN 1 
                    WHEN priority = 'high' THEN 2 
                    WHEN priority = 'medium' THEN 3 
                    WHEN priority = 'low' THEN 4 
                    ELSE 5 END");
                break;
            case 'priority_asc':
                $query->orderByRaw("CASE 
                    WHEN priority = 'low' THEN 1 
                    WHEN priority = 'medium' THEN 2 
                    WHEN priority = 'high' THEN 3 
                    WHEN priority = 'critical' THEN 4 
                    ELSE 5 END");
                break;
            case 'updated_at_desc':
                $query->orderBy('updated_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $tasks = $query->paginate(20);

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
        $statuses = TaskStatus::orderBy('order')->get();
        $types = TaskType::all();

        return view('tasks.global-index', compact('tasks', 'projects', 'users', 'statuses', 'types'));
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
        $statuses = TaskStatus::orderBy('order')->get();
        $types = TaskType::all();

        // Get all team members (users) for assignment - keep all users for standalone form
        // since user can change project and we'll validate on submission
        $assignableUsers = User::orderBy('name')->get();

        return view('tasks.create-standalone', compact('projects', 'project', 'assignableUsers', 'statuses', 'types'));
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
            'task_type_id' => 'required|exists:task_types,id',
            'priority' => 'required|in:low,medium,high,critical',
            'task_status_id' => 'required|exists:task_statuses,id',
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

        // Validate assignee is a member of the project
        if ($request->assignee_id) {
            $assignee = User::find($request->assignee_id);
            if (!$assignee || !$project->hasMember($assignee)) {
                return back()->withErrors(['assignee_id' => 'Assignee must be a member of the project.']);
            }
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'task_type_id' => $request->task_type_id,
            'priority' => $request->priority,
            'task_status_id' => $request->task_status_id,
            'project_id' => $request->project_id,
            'creator_id' => $user->id,
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

        $statuses = TaskStatus::orderBy('order')->get();
        $types = TaskType::all();

        // Get projects for admin users or user's projects
        if ($user->isAdmin()) {
            $projects = Project::all();
        } else {
            $projects = Project::where('owner_id', $user->id)
                ->orWhereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->get();
        }

        // Get all team members (users) for assignment - keep all users for standalone form
        // since user can change project and we'll validate on submission
        $assignableUsers = User::orderBy('name')->get();

        // Use the existing edit view with project context
        $project = $task->project;

        return view('tasks.edit', compact('task', 'project', 'assignableUsers', 'statuses', 'types'));
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
            'task_type_id' => 'required|exists:task_types,id',
            'priority' => 'required|in:low,medium,high,critical',
            'task_status_id' => 'required|exists:task_statuses,id',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'story_points' => 'nullable|integer|min:1|max:100',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip,rar'
        ]);

        // Verify user has access to the new project (if changed)
        $targetProject = $task->project;
        if ($request->project_id != $task->project_id) {
            $targetProject = Project::findOrFail($request->project_id);
            if (!$targetProject->hasMember($user)) {
                return back()->withErrors(['project_id' => 'You do not have access to this project.']);
            }
        }

        // Validate assignee is a member of the target project
        if ($request->assignee_id) {
            $assignee = User::find($request->assignee_id);
            if (!$assignee || !$targetProject->hasMember($assignee)) {
                return back()->withErrors(['assignee_id' => 'Assignee must be a member of the project.']);
            }
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'task_type_id' => $request->task_type_id,
            'priority' => $request->priority,
            'task_status_id' => $request->task_status_id,
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
     * Remove the specified task (standalone).
     */
    public function destroyStandalone(Task $task)
    {
        $user = Auth::user();

        // Check if user has access to this task's project
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this task.');
        }

        // Only creator, assignee, project owner, or admin can delete
        if (
            $task->creator_id !== $user->id &&
            $task->assignee_id !== $user->id &&
            $task->project->owner_id !== $user->id &&
            !$user->isAdmin()
        ) {
            abort(403, 'You do not have permission to delete this task.');
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

        $task->load(['project', 'assignee', 'creator', 'comments.user', 'blockingDependencies.dependentTask.project', 'blockedByDependencies.task.project']);

        // Use the existing show view with project context
        $project = $task->project;

        // Get IDs of all related tasks
        $blockingIds = $task->blockingDependencies->pluck('depends_on_task_id');
        $blockedByIds = $task->blockedByDependencies->pluck('task_id');
        $existingDependencyIds = $blockingIds->merge($blockedByIds)->unique();

        // Get tasks for the dropdown
        $availableTasks = $project->tasks()
            ->where('id', '!=', $task->id)
            ->whereNotIn('id', $existingDependencyIds)
            ->get();

        // Get all team members (users) for assignment
        $assignableUsers = User::orderBy('name')->get();
        $statuses = TaskStatus::orderBy('order')->get();
        $types = TaskType::all();

        return view('tasks.show', compact('task', 'project', 'assignableUsers', 'availableTasks', 'statuses', 'types'));
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

        $statuses = TaskStatus::orderBy('order')->get();
        $types = TaskType::all();

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

        return view('tasks.global-edit', compact('task', 'projects', 'assignableUsers', 'statuses', 'types'));
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
            'task_type_id' => 'required|exists:task_types,id',
            'priority' => 'required|in:low,medium,high,critical',
            'task_status_id' => 'required|exists:task_statuses,id',
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
            'task_type_id' => $request->task_type_id,
            'priority' => $request->priority,
            'task_status_id' => $request->task_status_id,
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

    /**
     * Upload image for task description.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $file = $request->file('image');

        // Generate unique filename
        $filename = 'desc_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Store in public/task-images directory
        $path = $file->storeAs('task-images', $filename, 'public');

        // Create database record for the description image
        $descriptionImage = \App\Models\TaskDescriptionImage::create([
            'task_id' => null, // Will be set when task is saved
            'filename' => $filename,
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => $user->id,
            'is_used' => true,
        ]);

        // Return the URL for the image
        $url = asset('storage/' . $path);

        return response()->json([
            'success' => true,
            'url' => $url,
            'filename' => $filename,
            'image_id' => $descriptionImage->id
        ]);
    }

    public function addDependency(Request $request, Task $task)
    {
        $request->validate([
            'related_task_id' => 'required|exists:tasks,id',
            'dependency_type' => 'required|in:blocks,is_blocked_by',
        ]);

        $relatedTask = Task::findOrFail($request->related_task_id);

        // Determine the blocker and the blocked task
        $blocker_id = ($request->dependency_type === 'blocks') ? $task->id : $relatedTask->id;
        $blocked_id = ($request->dependency_type === 'blocks') ? $relatedTask->id : $task->id;

        // Check for existing dependency
        $existingDependency = TaskDependency::where('task_id', $blocker_id)
            ->where('depends_on_task_id', $blocked_id)
            ->exists();

        if ($existingDependency) {
            return back()->with('error', 'This dependency already exists.');
        }

        TaskDependency::create([
            'task_id' => $blocker_id,
            'depends_on_task_id' => $blocked_id,
            'type' => 'blocks' // We always store the dependency in one direction
        ]);

        return back()->with('success', 'Dependency added successfully.');
    }

    public function removeDependency(Task $task, TaskDependency $dependency)
    {
        $dependency->delete();

        return back()->with('success', 'Dependency removed successfully.');
    }
}
