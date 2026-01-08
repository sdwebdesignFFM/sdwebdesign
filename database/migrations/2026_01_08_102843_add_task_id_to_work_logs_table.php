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
        Schema::table('work_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('work_logs', 'task_id')) {
                $table->foreignId('task_id')->nullable()->after('client_id')->constrained()->nullOnDelete();
                $table->index('task_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_logs', function (Blueprint $table) {
            if (Schema::hasColumn('work_logs', 'task_id')) {
                $table->dropForeign(['task_id']);
                $table->dropIndex(['task_id']);
                $table->dropColumn('task_id');
            }
        });
    }
};
