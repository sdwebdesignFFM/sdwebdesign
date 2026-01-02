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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number', 50)->unique(); // V-2026-0001
            $table->foreignId('quote_id')->nullable()->constrained()->nullOnDelete();

            // Typ
            $table->enum('type', ['one_time', 'recurring'])->default('one_time');

            // Kunde (kopiert von Quote)
            $table->string('client_name');
            $table->string('client_company')->nullable();
            $table->string('client_email');
            $table->string('client_phone', 50)->nullable();
            $table->text('client_address')->nullable();

            // Vertrag
            $table->string('title');
            $table->text('subject')->nullable(); // Vertragsgegenstand
            $table->text('terms_text')->nullable();

            // Preise
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_rate', 5, 2)->default(19);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('total', 10, 2);

            // Laufzeit (für recurring)
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'yearly'])->nullable();
            $table->integer('min_term_months')->nullable();
            $table->boolean('auto_renewal')->default(false);
            $table->integer('notice_period_days')->default(30);

            // Wichtige Daten
            $table->date('start_date');
            $table->date('min_term_end_date')->nullable(); // Ende Mindestlaufzeit
            $table->date('current_period_start')->nullable();
            $table->date('current_period_end')->nullable();
            $table->date('next_billing_date')->nullable();

            // Status
            $table->enum('status', ['active', 'cancelled', 'expired', 'completed'])->default('active');
            $table->timestamp('cancelled_at')->nullable();
            $table->date('cancellation_effective_date')->nullable();
            $table->text('cancellation_reason')->nullable();

            // Unterschrift
            $table->string('accepted_name');
            $table->timestamp('accepted_at');
            $table->string('accepted_ip', 45)->nullable();

            // PDF
            $table->string('pdf_path')->nullable();

            $table->timestamps();

            $table->index(['status', 'next_billing_date']);
            $table->index('client_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
