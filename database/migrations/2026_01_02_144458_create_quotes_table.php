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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number', 50)->unique(); // A-2026-0001
            $table->foreignId('template_id')->nullable()->constrained('quote_templates')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');

            // Typ
            $table->enum('type', ['one_time', 'recurring'])->default('one_time');

            // Kunde
            $table->string('client_name');
            $table->string('client_company')->nullable();
            $table->string('client_email');
            $table->string('client_phone', 50)->nullable();
            $table->text('client_address')->nullable();

            // Angebot
            $table->string('title');
            $table->text('subject')->nullable(); // Vertragsgegenstand (z.B. Domain)
            $table->text('intro_text')->nullable();
            $table->text('terms_text')->nullable();
            $table->text('footer_text')->nullable();
            $table->text('internal_notes')->nullable();

            // Preise
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(19);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            // Für Laufzeitverträge
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'yearly'])->nullable();
            $table->integer('min_term_months')->nullable();
            $table->boolean('auto_renewal')->default(false);
            $table->integer('notice_period_days')->nullable();
            $table->date('contract_start_date')->nullable();

            // Status & Dates
            $table->enum('status', ['draft', 'sent', 'viewed', 'accepted', 'declined', 'expired'])->default('draft');
            $table->date('valid_until')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('first_viewed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();

            // Akzeptierung
            $table->string('accepted_name')->nullable();
            $table->string('accepted_ip', 45)->nullable();

            // Security
            $table->string('token', 64)->unique();

            // Erinnerungen
            $table->integer('reminder_count')->default(0);
            $table->timestamp('last_reminder_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'valid_until']);
            $table->index('client_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
