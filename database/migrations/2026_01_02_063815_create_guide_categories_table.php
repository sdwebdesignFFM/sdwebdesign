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
        Schema::create('guide_categories', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('slug');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->foreignId('guide_category_id')
                ->nullable()
                ->after('parent_id')
                ->constrained('guide_categories')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['guide_category_id']);
            $table->dropColumn('guide_category_id');
        });

        Schema::dropIfExists('guide_categories');
    }
};
