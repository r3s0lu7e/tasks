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
        // Update tasks table status enum to include 'cancelled'
        if (Schema::hasTable('tasks')) {
            // First, let's check if the enum already includes 'cancelled'
            $hasCancelled = DB::table('tasks')->where('status', 'cancelled')->exists();

            if (!$hasCancelled) {
                // For SQLite compatibility, we'll drop and recreate the constraint
                // This is a safe approach that works across different database systems
                Schema::table('tasks', function (Blueprint $table) {
                    $table->string('status_temp')->nullable();
                });

                // Copy current status values to temp column
                DB::statement("UPDATE tasks SET status_temp = status");

                // Drop the old status column
                Schema::table('tasks', function (Blueprint $table) {
                    $table->dropColumn('status');
                });

                // Add the new status column with 'cancelled' included
                Schema::table('tasks', function (Blueprint $table) {
                    $table->enum('status', ['todo', 'in_progress', 'completed', 'blocked', 'cancelled'])->default('todo');
                });

                // Copy values back from temp column
                DB::statement("UPDATE tasks SET status = status_temp");

                // Drop the temp column
                Schema::table('tasks', function (Blueprint $table) {
                    $table->dropColumn('status_temp');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert tasks table status enum (remove 'cancelled')
        if (Schema::hasTable('tasks')) {
            // Update any 'cancelled' statuses to 'blocked' before removing the option
            DB::table('tasks')->where('status', 'cancelled')->update(['status' => 'blocked']);

            Schema::table('tasks', function (Blueprint $table) {
                $table->string('status_temp')->nullable();
            });

            DB::statement("UPDATE tasks SET status_temp = status");

            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('status');
            });

            Schema::table('tasks', function (Blueprint $table) {
                $table->enum('status', ['todo', 'in_progress', 'completed', 'blocked'])->default('todo');
            });

            DB::statement("UPDATE tasks SET status = status_temp");

            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('status_temp');
            });
        }
    }
};
