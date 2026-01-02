<?php

namespace App\Services\Quote;

use App\Enums\ContractStatus;
use App\Enums\InvoiceStatus;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\QuoteActivity;
use App\Settings\QuoteSettings;
use Illuminate\Support\Facades\DB;

class ContractService
{
    public function __construct(
        private QuoteNumberService $numberService,
        private QuoteSettings $settings
    ) {}

    /**
     * Cancel a contract.
     */
    public function cancel(Contract $contract, ?string $reason = null, ?\Carbon\Carbon $effectiveDate = null): bool
    {
        if (! $contract->canBeCancelled()) {
            return false;
        }

        $effectiveDate = $effectiveDate ?? $contract->getEarliestCancellationDate();

        $contract->update([
            'status' => ContractStatus::Cancelled,
            'cancelled_at' => now(),
            'cancellation_effective_date' => $effectiveDate,
            'cancellation_reason' => $reason,
        ]);

        QuoteActivity::logContractActivity(
            $contract,
            'cancelled',
            "Vertrag gekündigt zum {$effectiveDate->format('d.m.Y')}",
            [
                'reason' => $reason,
                'effective_date' => $effectiveDate->toDateString(),
            ]
        );

        return true;
    }

    /**
     * Renew a contract for another period.
     */
    public function renew(Contract $contract): bool
    {
        if ($contract->status !== ContractStatus::Active || ! $contract->isRecurring()) {
            return false;
        }

        $contract->advanceBillingPeriod();

        QuoteActivity::logContractActivity(
            $contract,
            'renewed',
            "Vertrag verlängert bis {$contract->current_period_end->format('d.m.Y')}"
        );

        return true;
    }

    /**
     * Generate invoice for contract period.
     */
    public function generateInvoice(Contract $contract, ?\Carbon\Carbon $periodStart = null, ?\Carbon\Carbon $periodEnd = null): ?Invoice
    {
        if ($contract->status !== ContractStatus::Active) {
            return null;
        }

        $periodStart = $periodStart ?? $contract->current_period_start;
        $periodEnd = $periodEnd ?? $contract->current_period_end;

        return DB::transaction(function () use ($contract, $periodStart, $periodEnd) {
            $invoice = Invoice::create([
                'invoice_number' => $this->numberService->generateInvoiceNumber(),
                'contract_id' => $contract->id,
                'client_name' => $contract->client_name,
                'client_company' => $contract->client_company,
                'client_email' => $contract->client_email,
                'client_address' => $contract->client_address,
                'subtotal' => $contract->subtotal,
                'tax_rate' => $contract->tax_rate,
                'tax_amount' => $contract->tax_amount,
                'total' => $contract->total,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'status' => InvoiceStatus::Draft,
                'issue_date' => now()->toDateString(),
                'due_date' => now()->addDays($this->settings->default_payment_terms_days)->toDateString(),
            ]);

            // Copy items from contract
            foreach ($contract->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'unit_price' => $item->recurring_price ?? $item->unit_price,
                    'total_price' => $item->recurring_price ?? $item->total_price,
                    'sort_order' => $item->sort_order,
                ]);
            }

            QuoteActivity::logContractActivity(
                $contract,
                'invoice_created',
                "Rechnung {$invoice->invoice_number} erstellt",
                ['invoice_id' => $invoice->id]
            );

            QuoteActivity::logInvoiceActivity(
                $invoice,
                'created',
                'Rechnung erstellt aus Vertrag'
            );

            return $invoice;
        });
    }

    /**
     * Get contracts that need billing.
     */
    public function getContractsNeedingBilling(): \Illuminate\Database\Eloquent\Collection
    {
        return Contract::needsBilling()->get();
    }

    /**
     * Process all contracts that need billing.
     */
    public function processRecurringBilling(): array
    {
        $contracts = $this->getContractsNeedingBilling();
        $results = [];

        foreach ($contracts as $contract) {
            $invoice = $this->generateInvoice($contract);
            if ($invoice) {
                $this->renew($contract);
                $results[] = [
                    'contract' => $contract->contract_number,
                    'invoice' => $invoice->invoice_number,
                ];
            }
        }

        return $results;
    }

    /**
     * Get contracts expiring within days.
     */
    public function getExpiringContracts(int $days = 30): \Illuminate\Database\Eloquent\Collection
    {
        return Contract::expiringMinTerm($days)->get();
    }
}
