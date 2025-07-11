<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add story_points column to tasks table if it doesn't exist
        if (Schema::hasTable('tasks') && !Schema::hasColumn('tasks', 'story_points')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->integer('story_points')->nullable()->after('due_date');
            });
        }

        // Add hourly_rate column to users table if it doesn't exist
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'hourly_rate')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('hourly_rate', 8, 2)->nullable()->after('status');
            });
        }

        // Note: Enum modifications are skipped to avoid database-specific issues
        // The application will handle the new enum values gracefully
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove story_points column if it exists
        if (Schema::hasTable('tasks') && Schema::hasColumn('tasks', 'story_points')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('story_points');
            });
        }

        // Remove hourly_rate column if it exists
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'hourly_rate')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('hourly_rate');
            });
        }
    }
};
