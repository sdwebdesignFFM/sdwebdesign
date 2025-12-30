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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Firmeninformationen
            $table->string('company_name')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('tagline')->nullable();

            // Kontaktdaten
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();

            // Adresse
            $table->string('street')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Deutschland');

            // Öffnungszeiten
            $table->string('business_hours')->nullable();

            // Social Media
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('xing_url')->nullable();
            $table->string('github_url')->nullable();

            // Rechtliches
            $table->string('vat_id')->nullable();
            $table->string('tax_number')->nullable();
            $table->text('imprint_extra')->nullable();

            // SEO Defaults
            $table->string('default_meta_title')->nullable();
            $table->text('default_meta_description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
