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
        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->date('worked_on');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('duration_minutes');
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->boolean('is_billed')->default(false);
            $table->timestamps();

            $table->index(['client_id', 'worked_on']);
            $table->index(['is_billed', 'worked_on']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_logs');
    }
};
