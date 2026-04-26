<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshop_requests', function (Blueprint $table) {
            $table->id();
            $table->string('workshop_slug')->index(); // 'plattform-discovery' for now
            // Step 1 — Vorhaben
            $table->text('trigger_question')->nullable();
            $table->string('industry')->nullable();
            $table->json('workflow_areas')->nullable();
            // Step 2 — Stand & Bestand
            $table->json('existing_systems')->nullable();
            $table->string('procurement_stage')->nullable();
            $table->string('budget_indication')->nullable();
            $table->string('go_live_timeline')->nullable();
            // Step 3 — Workshop-Format
            $table->string('workshop_format')->nullable();
            $table->string('preferred_timing')->nullable();
            $table->string('preferred_daytime')->nullable();
            // Step 4 — Kontakt
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('role')->nullable();
            $table->string('company_size')->nullable();
            $table->text('briefing_notes')->nullable();
            // Meta
            $table->string('locale', 5)->default('de');
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('admin_notified_at')->nullable();
            $table->timestamp('confirmation_sent_at')->nullable();
            $table->timestamps();

            $table->index(['workshop_slug', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshop_requests');
    }
};
