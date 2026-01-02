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
        Schema::create('contract_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2)->default(1);
            $table->string('unit', 50)->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);

            // Für recurring: Betrag pro Abrechnungsperiode
            $table->decimal('recurring_price', 10, 2)->nullable();

            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['contract_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_items');
    }
};
