<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\ProjectController;
// use App\Http\Controllers\Api\TaskController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('auth:sanctum')->group(function () {
//     // Project API routes
//     Route::apiResource('projects', ProjectController::class);
//     Route::post('/projects/{project}/members', [ProjectController::class, 'addMember']);
//     Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember']);
//     
//     // Task API routes
//     Route::apiResource('projects.tasks', TaskController::class);
//     Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
//     Route::patch('/tasks/{task}/assign', [TaskController::class, 'assign']);
// });
