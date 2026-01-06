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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('salutation')->nullable();
            $table->string('title', 50)->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone', 50)->nullable();
            $table->string('street')->nullable();
            $table->string('zip', 20)->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
