<?php

namespace App\Services\Quote;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\QuoteActivity;
use App\Settings\QuoteSettings;
use Illuminate\Database\Eloquent\Collection;

class InvoiceService
{
    public function __construct(
        private QuoteNumberService $numberService,
        private QuoteSettings $settings
    ) {}

    /**
     * Send invoice to client.
     */
    public function send(Invoice $invoice): bool
    {
        if ($invoice->status !== InvoiceStatus::Draft) {
            return false;
        }

        $invoice->update([
            'status' => InvoiceStatus::Sent,
            'sent_at' => now(),
        ]);

        QuoteActivity::logInvoiceActivity($invoice, 'sent', 'Rechnung an Kunde gesendet');

        return true;
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(Invoice $invoice, ?string $paymentMethod = null, ?string $reference = null): bool
    {
        if (! $invoice->canBePaid()) {
            return false;
        }

        $invoice->markAsPaid($paymentMethod, $reference);

        QuoteActivity::logInvoiceActivity(
            $invoice,
            'paid',
            'Rechnung als bezahlt markiert',
            [
                'payment_method' => $paymentMethod,
                'reference' => $reference,
            ]
        );

        return true;
    }

    /**
     * Cancel invoice.
     */
    public function cancel(Invoice $invoice, ?string $reason = null): bool
    {
        if (! $invoice->canBeCancelled()) {
            return false;
        }

        $invoice->update([
            'status' => InvoiceStatus::Cancelled,
            'cancelled_at' => now(),
            'cancellation_number' => $this->numberService->generateCancellationNumber(),
            'cancellation_reason' => $reason,
        ]);

        QuoteActivity::logInvoiceActivity(
            $invoice,
            'cancelled',
            'Rechnung storniert',
            ['reason' => $reason]
        );

        return true;
    }

    /**
     * Mark overdue invoices.
     */
    public function markOverdueInvoices(): int
    {
        $count = 0;

        $invoices = Invoice::where('status', InvoiceStatus::Sent)
            ->where('due_date', '<', now())
            ->get();

        foreach ($invoices as $invoice) {
            $invoice->update(['status' => InvoiceStatus::Overdue]);
            QuoteActivity::logInvoiceActivity($invoice, 'overdue', 'Rechnung als überfällig markiert');
            $count++;
        }

        return $count;
    }

    /**
     * Get invoices needing payment reminder.
     */
    public function getInvoicesNeedingReminder(): Collection
    {
        return Invoice::needsReminder()->get();
    }

    /**
     * Record payment reminder sent.
     */
    public function recordReminderSent(Invoice $invoice): void
    {
        $invoice->update([
            'reminder_count' => $invoice->reminder_count + 1,
            'last_reminder_at' => now(),
        ]);

        QuoteActivity::logInvoiceActivity(
            $invoice,
            'reminder_sent',
            "Zahlungserinnerung #{$invoice->reminder_count} gesendet"
        );
    }

    /**
     * Get overdue invoices.
     */
    public function getOverdueInvoices(): Collection
    {
        return Invoice::overdue()->get();
    }

    /**
     * Get open invoices.
     */
    public function getOpenInvoices(): Collection
    {
        return Invoice::open()->get();
    }

    /**
     * Get total open amount.
     */
    public function getTotalOpenAmount(): float
    {
        return Invoice::open()->sum('total');
    }

    /**
     * Get total overdue amount.
     */
    public function getTotalOverdueAmount(): float
    {
        return Invoice::overdue()->sum('total');
    }
}
