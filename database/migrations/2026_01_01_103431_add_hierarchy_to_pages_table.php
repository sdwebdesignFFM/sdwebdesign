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
        Schema::table('pages', function (Blueprint $table) {
            $table->foreignId('parent_id')
                ->nullable()
                ->after('id')
                ->constrained('pages')
                ->nullOnDelete();
            $table->integer('sort_order')->default(0)->after('is_active');
            $table->index(['parent_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['parent_id', 'sort_order']);
            $table->dropColumn(['parent_id', 'sort_order']);
        });
    }
};
