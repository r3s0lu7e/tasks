<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\TaskStatus;

class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     */
    public function show()
    {
        $user = Auth::user();

        $completedStatus = TaskStatus::where('alias', 'completed')->first();

        // Get user statistics
        $stats = [
            'owned_projects' => $user->ownedProjects()->count(),
            'member_projects' => $user->projects()->count(),
            'assigned_tasks' => $user->assignedTasks()->count(),
            'completed_tasks' => $completedStatus ? $user->assignedTasks()->where('task_status_id', $completedStatus->id)->count() : 0,
            'created_tasks' => $user->createdTasks()->count(),
        ];

        // Get recent activity
        $recentTasks = $user->assignedTasks()
            ->with(['project', 'status', 'type'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('profile.show', compact('user', 'stats', 'recentTasks'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        // Check if password is correct
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'The password is incorrect.']);
        }

        // Prevent admin users from deleting themselves if they're the only admin
        if ($user->isAdmin()) {
            $adminCount = \App\Models\User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->withErrors(['password' => 'Cannot delete the last admin account.']);
            }
        }

        // Reassign owned projects to another admin or delete them
        if ($user->ownedProjects()->count() > 0) {
            $anotherAdmin = \App\Models\User::where('role', 'admin')
                ->where('id', '!=', $user->id)
                ->first();

            if ($anotherAdmin) {
                $user->ownedProjects()->update(['owner_id' => $anotherAdmin->id]);
            } else {
                // If no other admin, delete the projects
                $user->ownedProjects()->delete();
            }
        }

        // Reassign created tasks to another admin or unassign them
        if ($user->createdTasks()->count() > 0) {
            $anotherAdmin = \App\Models\User::where('role', 'admin')
                ->where('id', '!=', $user->id)
                ->first();

            if ($anotherAdmin) {
                $user->createdTasks()->update(['creator_id' => $anotherAdmin->id]);
            }
        }

        // Unassign assigned tasks
        $user->assignedTasks()->update(['assignee_id' => null]);

        // Logout and delete the user
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Your account has been deleted successfully.');
    }

    /**
     * Update the user's dashboard layout.
     */
    public function updateDashboardLayout(Request $request)
    {
        $request->validate([
            'layout' => 'required|array'
        ]);

        $user = Auth::user();

        $user->update([
            'dashboard_layout' => $request->layout,
        ]);

        return response()->json(['success' => true]);
    }
}
