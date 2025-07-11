<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(Request $request, Task $task)
    {
        $user = Auth::user();

        // Check if user has access to this task's project
        if (!$task->project->hasMember($user)) {
            abort(403, 'You do not have access to this project.');
        }

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment = TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'content' => $request->content,
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully.',
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'user' => [
                    'name' => $comment->user->name,
                    'initials' => $comment->user->getInitials(),
                ],
                'created_at' => $comment->created_at->diffForHumans(),
            ]
        ]);
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, TaskComment $comment)
    {
        $user = Auth::user();

        // Check if user owns the comment, is admin, or has access to the project
        if ($comment->user_id !== $user->id && !$user->isAdmin() && !$comment->task->project->hasMember($user)) {
            abort(403, 'You do not have permission to edit this comment.');
        }

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully.',
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'updated_at' => $comment->updated_at->diffForHumans(),
            ]
        ]);
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(TaskComment $comment)
    {
        $user = Auth::user();

        // Check if user owns the comment, is admin, or has access to the project
        if ($comment->user_id !== $user->id && !$user->isAdmin() && !$comment->task->project->hasMember($user)) {
            abort(403, 'You do not have permission to delete this comment.');
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.',
        ]);
    }
}
