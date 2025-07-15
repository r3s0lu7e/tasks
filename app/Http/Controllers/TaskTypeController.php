<?php

namespace App\Http\Controllers;

use App\Models\TaskType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $types = TaskType::all();
        return view('task-types.index', compact('types'));
    }

    public function create()
    {
        return view('task-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:task_types',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50',
            'icon_color' => 'required|string|max:7',
        ]);

        TaskType::create($request->only(['name', 'color', 'icon', 'icon_color']));

        return redirect()->route('task-types.index')->with('success', 'Task type created successfully.');
    }

    public function edit(TaskType $taskType)
    {
        return view('task-types.edit', ['taskType' => $taskType]);
    }

    public function update(Request $request, TaskType $taskType)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('task_types')->ignore($taskType->id)],
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50',
            'icon_color' => 'required|string|max:7',
        ]);

        $taskType->update($request->only(['name', 'color', 'icon', 'icon_color']));

        return redirect()->route('task-types.index')->with('success', 'Task type updated successfully.');
    }

    public function destroy(TaskType $taskType)
    {
        if ($taskType->tasks()->count() > 0) {
            return redirect()->route('task-types.index')->with('error', 'Cannot delete type that is in use by tasks.');
        }

        $taskType->delete();

        return redirect()->route('task-types.index')->with('success', 'Task type deleted successfully.');
    }
}
