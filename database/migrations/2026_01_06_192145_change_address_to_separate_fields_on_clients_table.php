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
        if (Schema::hasColumn('clients', 'street')) {
            return;
        }

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->string('street')->nullable()->after('phone');
            $table->string('zip', 20)->nullable()->after('street');
            $table->string('city')->nullable()->after('zip');
            $table->string('country')->nullable()->default('Deutschland')->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['street', 'zip', 'city', 'country']);
            $table->text('address')->nullable()->after('phone');
        });
    }
};
