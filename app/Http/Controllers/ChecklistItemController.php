<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;

class ChecklistItemController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate(['content' => 'required|string|max:255']);

        $item = $task->checklistItems()->create([
            'content' => $request->content,
            'order' => $task->checklistItems()->count(),
        ]);

        return response()->json(['success' => true, 'item' => $item]);
    }

    public function update(Request $request, ChecklistItem $item)
    {
        $request->validate(['is_completed' => 'required|boolean']);

        $item->update(['is_completed' => $request->is_completed]);

        return response()->json(['success' => true]);
    }

    public function destroy(ChecklistItem $item)
    {
        $item->delete();
        return response()->json(['success' => true]);
    }
}
