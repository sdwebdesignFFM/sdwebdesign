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
        // Skip if already migrated (columns already exist in create migration)
        if (Schema::hasColumn('clients', 'last_name')) {
            return;
        }

        Schema::table('clients', function (Blueprint $table) {
            $table->string('last_name')->after('first_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['salutation', 'title', 'last_name']);
            $table->renameColumn('first_name', 'name');
        });
    }
};
