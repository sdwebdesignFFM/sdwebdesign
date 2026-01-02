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
        Schema::create('quote_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('contract_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Admin der Aktion

            $table->string('action', 50); // created, sent, viewed, accepted, declined, expired, etc.
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamp('created_at');

            $table->index(['quote_id', 'created_at']);
            $table->index(['contract_id', 'created_at']);
            $table->index(['invoice_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_activities');
    }
};
