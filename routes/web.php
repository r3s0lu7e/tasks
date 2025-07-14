<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Project routes
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}/members', [ProjectController::class, 'members'])->name('projects.members');
    Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.add-member');
    Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])->name('projects.remove-member');
    Route::get('/api/projects/{project}/members', [ProjectController::class, 'getProjectMembers'])->name('api.projects.members');

    // Task routes (nested under projects)
    Route::resource('projects.tasks', TaskController::class)->except(['index']);
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index'])->name('projects.tasks.index');

    // Standalone task routes for single-user convenience
    Route::get('/tasks', [TaskController::class, 'globalIndex'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'createStandalone'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'storeStandalone'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'showGlobal'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'editStandalone'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'updateStandalone'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroyGlobal'])->name('tasks.destroy');

    // Task actions
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::patch('/tasks/{task}/assign', [TaskController::class, 'assign'])->name('tasks.assign');

    // Task attachment routes
    Route::delete('/tasks/{task}/attachments/{attachment}', [TaskController::class, 'deleteAttachment'])->name('tasks.attachments.delete');
    Route::get('/tasks/{task}/attachments/{attachment}/download', [TaskController::class, 'downloadAttachment'])->name('tasks.attachments.download');

    // Task Dependency routes
    Route::post('/tasks/{task}/dependencies', [TaskController::class, 'addDependency'])->name('tasks.dependencies.add');
    Route::delete('/tasks/{task}/dependencies/{dependency}', [TaskController::class, 'removeDependency'])->name('tasks.dependencies.remove');

    // Checklist Item routes
    Route::post('/tasks/{task}/checklist-items', [App\Http\Controllers\ChecklistItemController::class, 'store'])->name('checklist-items.store');
    Route::put('/checklist-items/{item}', [App\Http\Controllers\ChecklistItemController::class, 'update'])->name('checklist-items.update');
    Route::delete('/checklist-items/{item}', [App\Http\Controllers\ChecklistItemController::class, 'destroy'])->name('checklist-items.destroy');

    // Image upload route for descriptions
    Route::post('/upload/image', [TaskController::class, 'uploadImage'])->name('upload.image');

    // Task Comments routes
    Route::post('/tasks/{task}/comments', [App\Http\Controllers\TaskCommentController::class, 'store'])->name('tasks.comments.store');
    Route::put('/comments/{comment}', [App\Http\Controllers\TaskCommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [App\Http\Controllers\TaskCommentController::class, 'destroy'])->name('comments.destroy');

    // Team Management routes - Admin only
    Route::middleware(['admin'])->group(function () {
        Route::resource('team', App\Http\Controllers\TeamMemberController::class);
        Route::get('/team/workload/data', [App\Http\Controllers\TeamMemberController::class, 'workloadData'])->name('team.workload.data');
    });

    // Profile routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Search routes
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search.index');
    Route::get('/search/suggestions', [App\Http\Controllers\SearchController::class, 'suggestions'])->name('search.suggestions');

    // Personal Notes routes
    Route::resource('personal-notes', App\Http\Controllers\PersonalNotesController::class);
    Route::post('/personal-notes/{personalNote}/toggle-pin', [App\Http\Controllers\PersonalNotesController::class, 'togglePin'])->name('personal-notes.toggle-pin');
    Route::post('/personal-notes/{personalNote}/toggle-favorite', [App\Http\Controllers\PersonalNotesController::class, 'toggleFavorite'])->name('personal-notes.toggle-favorite');
    Route::get('/api/personal-notes/quick-access', [App\Http\Controllers\PersonalNotesController::class, 'quickAccess'])->name('personal-notes.quick-access');
    Route::get('/api/personal-notes/tag-suggestions', [App\Http\Controllers\PersonalNotesController::class, 'tagSuggestions'])->name('personal-notes.tag-suggestions');

    // Saved Filter routes
    Route::post('/filters/save', [App\Http\Controllers\SavedFilterController::class, 'save'])->name('filters.save');
    Route::delete('/filters/{id}', [App\Http\Controllers\SavedFilterController::class, 'destroy'])->name('filters.destroy');

    // Period Calendar routes (restricted to iva@wuvu.com)
    Route::resource('period-calendar', App\Http\Controllers\PeriodCalendarController::class);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
