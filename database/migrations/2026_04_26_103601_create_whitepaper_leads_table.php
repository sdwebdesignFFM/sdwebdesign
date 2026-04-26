<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whitepaper_leads', function (Blueprint $table) {
            $table->id();
            $table->string('whitepaper_slug')->index();
            $table->string('email');
            $table->string('name')->nullable();
            $table->string('company')->nullable();
            $table->string('role')->nullable();
            $table->string('locale', 5)->default('de');
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->boolean('newsletter_opt_in')->default(false);
            $table->timestamps();

            $table->unique(['whitepaper_slug', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whitepaper_leads');
    }
};
