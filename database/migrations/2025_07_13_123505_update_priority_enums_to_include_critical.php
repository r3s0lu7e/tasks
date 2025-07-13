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
        // For SQLite, we need to recreate the tables with the new enum values
        // For other databases, we would use ALTER TABLE statements

        // Update tasks table priority enum
        if (Schema::hasTable('tasks')) {
            // First, let's check if the enum already includes 'critical'
            $hasTasksCritical = DB::table('tasks')->where('priority', 'critical')->exists();

            if (!$hasTasksCritical) {
                // For SQLite compatibility, we'll drop and recreate the constraint
                // This is a safe approach that works across different database systems
                Schema::table('tasks', function (Blueprint $table) {
                    $table->string('priority_temp')->nullable();
                });

                // Copy current priority values to temp column
                DB::statement("UPDATE tasks SET priority_temp = priority");

                // Drop the old priority column
                Schema::table('tasks', function (Blueprint $table) {
                    $table->dropColumn('priority');
                });

                // Add the new priority column with 'critical' included
                Schema::table('tasks', function (Blueprint $table) {
                    $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
                });

                // Copy values back from temp column
                DB::statement("UPDATE tasks SET priority = priority_temp");

                // Drop the temp column
                Schema::table('tasks', function (Blueprint $table) {
                    $table->dropColumn('priority_temp');
                });
            }
        }

        // Update projects table priority enum
        if (Schema::hasTable('projects')) {
            // First, let's check if the enum already includes 'critical'
            $hasProjectsCritical = DB::table('projects')->where('priority', 'critical')->exists();

            if (!$hasProjectsCritical) {
                // For SQLite compatibility, we'll drop and recreate the constraint
                Schema::table('projects', function (Blueprint $table) {
                    $table->string('priority_temp')->nullable();
                });

                // Copy current priority values to temp column
                DB::statement("UPDATE projects SET priority_temp = priority");

                // Drop the old priority column
                Schema::table('projects', function (Blueprint $table) {
                    $table->dropColumn('priority');
                });

                // Add the new priority column with 'critical' included
                Schema::table('projects', function (Blueprint $table) {
                    $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
                });

                // Copy values back from temp column
                DB::statement("UPDATE projects SET priority = priority_temp");

                // Drop the temp column
                Schema::table('projects', function (Blueprint $table) {
                    $table->dropColumn('priority_temp');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert tasks table priority enum (remove 'critical')
        if (Schema::hasTable('tasks')) {
            // Update any 'critical' priorities to 'high' before removing the option
            DB::table('tasks')->where('priority', 'critical')->update(['priority' => 'high']);

            Schema::table('tasks', function (Blueprint $table) {
                $table->string('priority_temp')->nullable();
            });

            DB::statement("UPDATE tasks SET priority_temp = priority");

            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('priority');
            });

            Schema::table('tasks', function (Blueprint $table) {
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            });

            DB::statement("UPDATE tasks SET priority = priority_temp");

            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('priority_temp');
            });
        }

        // Revert projects table priority enum (remove 'critical')
        if (Schema::hasTable('projects')) {
            // Update any 'critical' priorities to 'high' before removing the option
            DB::table('projects')->where('priority', 'critical')->update(['priority' => 'high']);

            Schema::table('projects', function (Blueprint $table) {
                $table->string('priority_temp')->nullable();
            });

            DB::statement("UPDATE projects SET priority_temp = priority");

            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('priority');
            });

            Schema::table('projects', function (Blueprint $table) {
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            });

            DB::statement("UPDATE projects SET priority = priority_temp");

            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('priority_temp');
            });
        }
    }
};
