<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\TaskStatus;
use App\Models\TaskType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'project_manager', 'developer', 'tester', 'user'])->default('user');
            $table->string('avatar')->nullable();
            $table->string('department')->nullable();
            $table->string('phone')->nullable();
            $table->enum('status', ['active', 'inactive', 'vacation', 'busy'])->default('active');
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->date('hire_date')->nullable();
            $table->text('notes')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Password Resets Table
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Task Statuses Table
        Schema::create('task_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias')->unique()->nullable();
            $table->string('color', 7)->default('#808080');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Task Types Table
        Schema::create('task_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color', 7)->default('#8B5CF6');
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Projects Table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('key')->unique();
            $table->enum('status', ['planning', 'active', 'on_hold', 'completed', 'cancelled'])->default('planning');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('color', 7)->default('#3B82F6');
            $table->timestamps();
            $table->softDeletes();
        });

        // Project Members Pivot Table
        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Tasks Table
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('task_status_id')->constrained('task_statuses')->onDelete('restrict');
            $table->foreignId('task_type_id')->constrained('task_types')->onDelete('restrict');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('assignee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->date('due_date')->nullable();
            $table->integer('story_points')->nullable();
            $table->decimal('estimated_hours', 5, 2)->nullable();
            $table->decimal('actual_hours', 5, 2)->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('task_status_id');
            $table->index('due_date');
            $table->index('assignee_id');
            $table->index('project_id');
            $table->index(['task_status_id', 'due_date']);
            $table->index(['assignee_id', 'task_status_id']);
            $table->index(['project_id', 'task_status_id']);
            $table->index('updated_at');
        });

        // Task Comments Table
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
        });

        // Task Attachments Table (for file uploads)
        Schema::create('task_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('path');
            $table->unsignedBigInteger('size');
            $table->string('mime_type');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Indexes for better performance
            $table->index('task_id');
            $table->index('uploaded_by');
            $table->index('mime_type');
        });

        // Task Description Images Table (for pasted images in descriptions)
        Schema::create('task_description_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('set null');
            $table->string('filename');
            $table->string('path');
            $table->unsignedBigInteger('size');
            $table->string('mime_type');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_used')->default(true); // Track if image is still referenced in description
            $table->timestamps();

            // Indexes for better performance
            $table->index('task_id');
            $table->index('uploaded_by');
            $table->index('filename');
            $table->index('is_used');
        });

        // Checklist Items Table
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->string('content');
            $table->boolean('is_completed')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Task Dependencies Table
        Schema::create('task_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('depends_on_task_id')->constrained('tasks')->onDelete('cascade');
            $table->string('type')->default('blocks'); // e.g., 'blocks', 'is_blocked_by'
            $table->timestamps();
        });

        // Sessions, Cache, Jobs
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        // Saved Filters Table
        Schema::create('saved_filters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('filters');
            $table->timestamps();
        });

        // Period Calendar Table
        Schema::create('period_calendar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('cycle_length')->nullable();
            $table->integer('period_length')->nullable();
            $table->string('flow_intensity')->nullable();
            $table->text('symptoms')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Personal Notes Table
        Schema::create('personal_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('color', 7)->default('#fbbf24');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_favorite')->default(false);
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create default data
        $this->createDefaultData();

        // Add indexes to projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->index('status');
            $table->index('owner_id');
            $table->index(['status', 'created_at']);
        });

        // Add indexes to users table
        Schema::table('users', function (Blueprint $table) {
            $table->index('status');
            $table->index('role');
        });

        // Add index to project_members pivot table
        Schema::table('project_members', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('task_dependencies');
        Schema::dropIfExists('checklist_items');
        Schema::dropIfExists('task_description_images');
        Schema::dropIfExists('task_attachments');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('project_members');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('task_types');
        Schema::dropIfExists('task_statuses');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('period_calendar');
        Schema::dropIfExists('saved_filters');
        Schema::dropIfExists('personal_notes');
        Schema::dropIfExists('users');
    }

    private function createDefaultData()
    {
        // Default Task Statuses
        TaskStatus::create(['name' => 'To Do', 'alias' => 'todo', 'color' => '#808080', 'order' => 1]);
        TaskStatus::create(['name' => 'In Progress', 'alias' => 'in_progress', 'color' => '#3B82F6', 'order' => 2]);
        TaskStatus::create(['name' => 'Completed', 'alias' => 'completed', 'color' => '#22C55E', 'order' => 3]);
        TaskStatus::create(['name' => 'Cancelled', 'alias' => 'cancelled', 'color' => '#EF4444', 'order' => 4]);

        // Default Task Types
        TaskType::create(['name' => 'Story', 'color' => '#3B82F6', 'icon' => 'fa-book']);
        TaskType::create(['name' => 'Bug', 'color' => '#EF4444', 'icon' => 'fa-bug']);
        TaskType::create(['name' => 'Task', 'color' => '#808080', 'icon' => 'fa-check-square']);
    }
};
