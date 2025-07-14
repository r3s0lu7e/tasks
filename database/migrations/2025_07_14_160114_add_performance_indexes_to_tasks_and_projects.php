<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to tasks table for performance
        Schema::table('tasks', function (Blueprint $table) {
            $table->index('status');
            $table->index('due_date');
            $table->index('assignee_id');
            $table->index('project_id');
            $table->index(['status', 'due_date']);
            $table->index(['assignee_id', 'status']);
            $table->index(['project_id', 'status']);
            $table->index('updated_at');
        });

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
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['assignee_id']);
            $table->dropIndex(['project_id']);
            $table->dropIndex(['status', 'due_date']);
            $table->dropIndex(['assignee_id', 'status']);
            $table->dropIndex(['project_id', 'status']);
            $table->dropIndex(['updated_at']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['owner_id']);
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['role']);
        });

        Schema::table('project_members', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['project_id']);
        });
    }
};
