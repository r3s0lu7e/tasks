<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        $user = auth()->user();

        if (empty($query)) {
            return view('search.index', [
                'query' => '',
                'projects' => collect(),
                'tasks' => collect(),
                'total' => 0
            ]);
        }

        // Search projects based on user access
        if ($user->isAdmin()) {
            $projects = Project::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->orWhere('key', 'like', "%{$query}%")
                ->limit(10)
                ->get();
        } else {
            $projects = Project::where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('key', 'like', "%{$query}%");
            })->where(function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    });
            })->limit(10)
                ->get();
        }

        // Search tasks based on user access
        if ($user->isAdmin()) {
            $tasks = Task::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->with(['project', 'assignee'])
                ->limit(20)
                ->get();
        } else {
            $tasks = Task::where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })->whereHas('project', function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    });
            })->with(['project', 'assignee'])
                ->limit(20)
                ->get();
        }

        $total = $projects->count() + $tasks->count();

        return view('search.index', [
            'query' => $query,
            'projects' => $projects,
            'tasks' => $tasks,
            'total' => $total
        ]);
    }

    public function suggestions(Request $request)
    {
        $query = $request->get('q');
        $user = auth()->user();

        if (empty($query)) {
            return response()->json([]);
        }

        // Get quick suggestions based on user access
        if ($user->isAdmin()) {
            $projects = Project::where('name', 'like', "%{$query}%")
                ->select('id', 'name', 'key', 'color')
                ->limit(5)
                ->get()
                ->map(function ($project) {
                    return [
                        'type' => 'project',
                        'id' => $project->id,
                        'title' => $project->name,
                        'subtitle' => $project->key,
                        'color' => $project->color,
                        'url' => route('projects.show', $project)
                    ];
                });
        } else {
            $projects = Project::where('name', 'like', "%{$query}%")
                ->where(function ($q) use ($user) {
                    $q->where('owner_id', $user->id)
                        ->orWhereHas('members', function ($memberQuery) use ($user) {
                            $memberQuery->where('user_id', $user->id);
                        });
                })
                ->select('id', 'name', 'key', 'color')
                ->limit(5)
                ->get()
                ->map(function ($project) {
                    return [
                        'type' => 'project',
                        'id' => $project->id,
                        'title' => $project->name,
                        'subtitle' => $project->key,
                        'color' => $project->color,
                        'url' => route('projects.show', $project)
                    ];
                });
        }

        if ($user->isAdmin()) {
            $tasks = Task::where('title', 'like', "%{$query}%")
                ->with('project:id,name,color')
                ->select('id', 'title', 'project_id', 'status', 'priority')
                ->limit(5)
                ->get()
                ->map(function ($task) {
                    return [
                        'type' => 'task',
                        'id' => $task->id,
                        'title' => $task->title,
                        'subtitle' => $task->project->name,
                        'color' => $task->project->color,
                        'status' => $task->status,
                        'priority' => $task->priority,
                        'url' => route('tasks.show', $task)
                    ];
                });
        } else {
            $tasks = Task::where('title', 'like', "%{$query}%")
                ->whereHas('project', function ($q) use ($user) {
                    $q->where('owner_id', $user->id)
                        ->orWhereHas('members', function ($memberQuery) use ($user) {
                            $memberQuery->where('user_id', $user->id);
                        });
                })
                ->with('project:id,name,color')
                ->select('id', 'title', 'project_id', 'status', 'priority')
                ->limit(5)
                ->get()
                ->map(function ($task) {
                    return [
                        'type' => 'task',
                        'id' => $task->id,
                        'title' => $task->title,
                        'subtitle' => $task->project->name,
                        'color' => $task->project->color,
                        'status' => $task->status,
                        'priority' => $task->priority,
                        'url' => route('tasks.show', $task)
                    ];
                });
        }

        return response()->json($projects->merge($tasks));
    }
}
