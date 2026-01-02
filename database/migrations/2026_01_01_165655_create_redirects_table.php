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
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_url')->unique();
            $table->string('to_url');
            $table->integer('status_code')->default(301);
            $table->integer('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->index('from_url');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
