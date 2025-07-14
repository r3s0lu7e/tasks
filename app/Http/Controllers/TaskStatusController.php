<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $statuses = TaskStatus::orderBy('order')->get();
        return view('task-statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('task-statuses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:task_statuses',
            'color' => 'required|string|max:7',
            'order' => 'required|integer',
            'alias' => 'nullable|string|max:50|unique:task_statuses,alias',
        ]);

        $rgb = hex_to_rgb($request->color);

        TaskStatus::create([
            'name' => $request->name,
            'color' => $request->color,
            'rgb_color' => "{$rgb['r']}, {$rgb['g']}, {$rgb['b']}",
            'order' => $request->order,
            'alias' => $request->alias,
        ]);

        return redirect()->route('task-statuses.index')->with('success', 'Task status created successfully.');
    }

    public function edit(TaskStatus $taskStatus)
    {
        return view('task-statuses.edit', ['status' => $taskStatus]);
    }

    public function update(Request $request, TaskStatus $taskStatus)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('task_statuses')->ignore($taskStatus->id)],
            'color' => 'required|string|max:7',
            'order' => 'required|integer',
            'alias' => ['nullable', 'string', 'max:50', Rule::unique('task_statuses', 'alias')->ignore($taskStatus->id)],
        ]);

        $rgb = hex_to_rgb($request->color);

        $data = $request->only('name', 'color', 'order');
        $data['rgb_color'] = "{$rgb['r']}, {$rgb['g']}, {$rgb['b']}";

        if ($taskStatus->alias !== 'completed') {
            $data['alias'] = $request->alias;
        }

        $taskStatus->update($data);

        return redirect()->route('task-statuses.index')->with('success', 'Task status updated successfully.');
    }

    public function destroy(TaskStatus $taskStatus)
    {
        if ($taskStatus->alias === 'completed') {
            return redirect()->route('task-statuses.index')->with('error', 'The "Completed" status cannot be deleted.');
        }

        if ($taskStatus->tasks()->count() > 0) {
            return redirect()->route('task-statuses.index')->with('error', 'Cannot delete status that is in use by tasks.');
        }

        $taskStatus->delete();

        return redirect()->route('task-statuses.index')->with('success', 'Task status deleted successfully.');
    }
}
