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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // wartung, hosting, entwicklung, beratung

            // Preismodell
            $table->enum('type', ['one_time', 'recurring'])->default('one_time');
            $table->decimal('default_price', 10, 2)->nullable();
            $table->string('default_unit', 50)->nullable(); // pauschal, stunde, monat, jahr

            // Für recurring
            $table->json('billing_cycles')->nullable(); // ['monthly', 'quarterly', 'yearly']
            $table->json('prices_by_cycle')->nullable(); // {"monthly": 30, "quarterly": 85, "yearly": 300}

            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
