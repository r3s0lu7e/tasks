<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of team members.
     */
    public function index(Request $request)
    {
        $query = User::withCount(['assignedTasks', 'createdTasks']);

        // Apply status filter if provided
        if ($request->filled('status')) {
            if ($request->status !== 'all') {
                $query->where('status', $request->status);
            }
        }

        // Apply role filter if provided
        if ($request->filled('role')) {
            if ($request->role !== 'all') {
                $query->where('role', $request->role);
            }
        }

        // Apply search filter if provided
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('department', 'like', '%' . $request->search . '%');
            });
        }

        $teamMembers = $query->orderBy('name')->get();

        // Get available statuses and roles for filters
        $statuses = User::select('status')->distinct()->pluck('status');
        $roles = User::select('role')->distinct()->pluck('role');

        return view('team.index', compact('teamMembers', 'statuses', 'roles'));
    }

    /**
     * Show the form for creating a new team member.
     */
    public function create()
    {
        return view('team.create');
    }

    /**
     * Store a newly created team member.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => ['required', Rule::in(User::select('role')->distinct()->pluck('role'))],
            'status' => ['required', Rule::in(User::select('status')->distinct()->pluck('status'))],
            'hourly_rate' => 'nullable|numeric|min:0|max:999.99',
            'hire_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password123'), // Default password
            'department' => $request->department,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->status,
            'hourly_rate' => $request->hourly_rate,
            'hire_date' => $request->hire_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('team.index')
            ->with('success', 'Team member added successfully.');
    }

    /**
     * Display the specified team member.
     */
    public function show(User $team)
    {
        // Special handling for boss lady (ID 1) clicking on herself
        if (auth()->id() === 1 && $team->id === 1) {
            // Boss lady viewing herself - ensure we have the correct user
            $team = User::find(1);
            if (!$team) {
                abort(404, 'User not found');
            }
        }

        $team->load(['assignedTasks.project', 'assignedTasks.status', 'assignedTasks.type', 'createdTasks.project']);

        $statuses = TaskStatus::all();
        $completedStatus = $statuses->where('alias', 'completed')->first();
        $activeStatuses = $statuses->whereNotIn('alias', ['completed', 'cancelled']);

        // Get performance metrics
        $totalTasks = $team->assignedTasks()->count();
        $completedTasks = $completedStatus ? $team->assignedTasks()->where('task_status_id', $completedStatus->id)->count() : 0;
        $overdueTasks = $team->getOverdueTasksCount();
        $currentWorkload = $team->assignedTasks()->whereIn('task_status_id', $activeStatuses->pluck('id'))->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;


        return view('team.show', compact(
            'team',
            'totalTasks',
            'completedTasks',
            'overdueTasks',
            'currentWorkload',
            'completionRate'
        ));
    }

    /**
     * Show the form for editing the specified team member.
     */
    public function edit(User $team)
    {
        // Special handling for boss lady (ID 1) editing herself
        if (auth()->id() === 1 && $team->id === 1) {
            // Boss lady editing herself - ensure we have the correct user
            $team = User::find(1);
            if (!$team) {
                abort(404, 'User not found');
            }
        }

        return view('team.edit', [
            'team' => $team,
            'roles' => User::ROLES,
            'statuses' => User::STATUSES,
        ]);
    }

    /**
     * Update the specified team member.
     */
    public function update(Request $request, User $team)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $team->id,
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => ['required', Rule::in(User::ROLES)],
            'status' => ['required', Rule::in(User::STATUSES)],
            'hourly_rate' => 'nullable|numeric|min:0|max:999.99',
            'hire_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check if trying to change admin role
        if ($team->isAdmin() && $request->role !== $team->role) {
            // Only allow admin role changes if the current user is also an admin
            if (!auth()->user()->isAdmin()) {
                return redirect()->back()
                    ->withErrors(['role' => 'Only administrators can change admin roles.'])
                    ->withInput();
            }
        }

        $team->update($request->only([
            'name',
            'email',
            'department',
            'phone',
            'role',
            'status',
            'hourly_rate',
            'hire_date',
            'notes'
        ]));

        return redirect()->route('team.show', $team)
            ->with('success', 'Team member updated successfully.');
    }

    /**
     * Remove the specified team member.
     */
    public function destroy(User $team)
    {
        // Don't allow deleting the main user
        if ($team->id === auth()->id()) {
            return redirect()->route('team.index')
                ->with('error', 'You cannot delete yourself.');
        }

        // Reassign tasks to the main user before deletion
        $team->assignedTasks()->update(['assignee_id' => auth()->id()]);

        $team->delete();

        return redirect()->route('team.index')
            ->with('success', 'Team member removed successfully. Their tasks have been reassigned to you.');
    }

    /**
     * Get team member workload data for charts.
     */
    public function workloadData()
    {
        $teamMembers = User::where('id', '!=', auth()->id())
            ->active()
            ->get()
            ->map(function ($member) {
                return [
                    'name' => $member->name,
                    'current_workload' => $member->getCurrentWorkload(),
                    'completion_rate' => $member->getCompletionRate(),
                    'overdue_tasks' => $member->getOverdueTasksCount(),
                    'status' => $member->status,
                ];
            });

        return response()->json($teamMembers);
    }
}
