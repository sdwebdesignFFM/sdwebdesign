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
        Schema::create('quote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2)->default(1);
            $table->string('unit', 50)->nullable(); // Stück, Stunde, Monat, Pauschal
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);

            // Optionale Positionen
            $table->boolean('is_optional')->default(false);
            $table->boolean('is_selected')->default(true);
            $table->string('option_group', 50)->nullable(); // 'option_a', 'option_b' für A/B Auswahl

            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['quote_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_items');
    }
};
