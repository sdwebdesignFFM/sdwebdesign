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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('cta_image')->nullable();
            $table->string('cta_title')->nullable();
            $table->text('cta_subtitle')->nullable();
            $table->string('cta_name')->nullable();
            $table->string('cta_role')->nullable();
            $table->string('cta_button_text')->nullable();
            $table->string('cta_secondary_button_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'cta_image',
                'cta_title',
                'cta_subtitle',
                'cta_name',
                'cta_role',
                'cta_button_text',
                'cta_secondary_button_text',
            ]);
        });
    }
};
