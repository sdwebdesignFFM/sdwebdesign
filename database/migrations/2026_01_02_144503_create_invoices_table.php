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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique(); // R-2026-0001
            $table->foreignId('contract_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('quote_id')->nullable()->constrained()->nullOnDelete(); // Für Einmalzahlungen

            // Kunde
            $table->string('client_name');
            $table->string('client_company')->nullable();
            $table->string('client_email');
            $table->text('client_address')->nullable();

            // Beträge
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_rate', 5, 2)->default(19);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('total', 10, 2);

            // Zeitraum (für recurring)
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();

            // Status
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->date('issue_date');
            $table->date('due_date');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            // Zahlungsdetails
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_intent_id')->nullable(); // For Stripe

            // Storno
            $table->string('cancellation_number', 50)->nullable();
            $table->string('cancellation_pdf_path')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            // PDF
            $table->string('pdf_path')->nullable();

            // Erinnerungen
            $table->integer('reminder_count')->default(0);
            $table->timestamp('last_reminder_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'due_date']);
            $table->index('client_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
