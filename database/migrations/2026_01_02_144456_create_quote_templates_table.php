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
        Schema::create('quote_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['one_time', 'recurring'])->default('one_time');

            // Inhalte
            $table->text('intro_text')->nullable();
            $table->text('terms_text')->nullable(); // AGB / Vertragsbedingungen
            $table->text('footer_text')->nullable();

            // Defaults
            $table->integer('default_validity_days')->default(30);
            $table->json('default_items')->nullable();

            // Für Laufzeitverträge
            $table->integer('default_min_term_months')->nullable();
            $table->string('default_billing_cycle', 20)->nullable(); // monthly, quarterly, yearly
            $table->boolean('default_auto_renewal')->default(false);
            $table->integer('default_notice_period_days')->default(30); // Kündigungsfrist

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_templates');
    }
};
