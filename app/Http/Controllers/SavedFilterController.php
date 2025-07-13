<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedFilterController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'filters' => 'required|array'
        ]);

        Auth::user()->savedFilters()->create([
            'name' => $request->name,
            'filters' => $request->filters,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $savedFilter = Auth::user()->savedFilters()->findOrFail($id);
        $savedFilter->delete();

        return response()->json(['success' => true]);
    }
}
