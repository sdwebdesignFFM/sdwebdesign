<?php

namespace App\Services\Quote;

use App\Enums\ContractStatus;
use App\Enums\QuoteStatus;
use App\Mail\QuoteAcceptedAdmin;
use App\Mail\QuoteAcceptedClient;
use App\Mail\QuoteNotification;
use App\Models\Contract;
use App\Models\ContractItem;
use App\Models\Quote;
use App\Models\QuoteActivity;
use App\Models\Setting;
use App\Settings\QuoteSettings;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class QuoteService
{
    public function __construct(
        private QuoteNumberService $numberService,
        private QuoteSettings $settings
    ) {}

    /**
     * Create a new quote with items from template.
     */
    public function createFromTemplate(array $data, ?int $templateId = null): Quote
    {
        $data['quote_number'] = $this->numberService->generateQuoteNumber();
        $data['tax_rate'] = $data['tax_rate'] ?? $this->settings->default_tax_rate;
        $data['valid_until'] = $data['valid_until'] ?? now()->addDays($this->settings->default_validity_days);

        $quote = Quote::create($data);

        QuoteActivity::logQuoteActivity($quote, 'created', 'Angebot erstellt');

        return $quote;
    }

    /**
     * Send quote to client.
     */
    public function send(Quote $quote): bool
    {
        if ($quote->status !== QuoteStatus::Draft) {
            return false;
        }

        $quote->update([
            'status' => QuoteStatus::Sent,
            'sent_at' => now(),
        ]);

        // Send email notification to client
        Mail::to($quote->client_email)->send(new QuoteNotification($quote));

        QuoteActivity::logQuoteActivity($quote, 'sent', 'Angebot an Kunde gesendet');

        return true;
    }

    /**
     * Mark quote as viewed (called when client opens the link).
     */
    public function markAsViewed(Quote $quote): void
    {
        if ($quote->status === QuoteStatus::Sent && ! $quote->first_viewed_at) {
            $quote->update([
                'status' => QuoteStatus::Viewed,
                'first_viewed_at' => now(),
            ]);

            QuoteActivity::logQuoteActivity(
                $quote,
                'viewed',
                'Angebot vom Kunden angesehen',
                ['ip' => request()->ip()],
                null
            );
        }
    }

    /**
     * Accept quote and create contract.
     */
    public function accept(Quote $quote, string $acceptedName): ?Contract
    {
        if (! $quote->canBeAccepted()) {
            return null;
        }

        return DB::transaction(function () use ($quote, $acceptedName) {
            // Update quote status
            $quote->update([
                'status' => QuoteStatus::Accepted,
                'accepted_at' => now(),
                'accepted_name' => $acceptedName,
                'accepted_ip' => request()->ip(),
            ]);

            // Calculate totals with selected options
            $quote->calculateTotals();
            $quote->save();

            // Create contract from quote
            $contract = $this->createContractFromQuote($quote);

            QuoteActivity::logQuoteActivity(
                $quote,
                'accepted',
                "Angebot angenommen von: {$acceptedName}",
                [
                    'accepted_name' => $acceptedName,
                    'ip' => request()->ip(),
                    'contract_id' => $contract->id,
                ],
                null
            );

            // Generate service terms PDF if any items have detailed terms
            $serviceTermsPdf = $this->generateServiceTermsPdf($quote);

            // Send confirmation email to client with PDF attachments
            Mail::to($quote->client_email)->send(new QuoteAcceptedClient($quote, $contract, $serviceTermsPdf));

            // Send notification email to admin
            $settings = Setting::instance();
            if ($settings->email) {
                Mail::to($settings->email)->send(new QuoteAcceptedAdmin($quote, $contract));
            }

            return $contract;
        });
    }

    /**
     * Decline quote.
     */
    public function decline(Quote $quote): bool
    {
        if ($quote->status->isFinal()) {
            return false;
        }

        $quote->update([
            'status' => QuoteStatus::Declined,
        ]);

        QuoteActivity::logQuoteActivity($quote, 'declined', 'Angebot abgelehnt');

        return true;
    }

    /**
     * Mark quote as expired.
     */
    public function markExpired(Quote $quote): bool
    {
        if ($quote->status->isFinal()) {
            return false;
        }

        $quote->update([
            'status' => QuoteStatus::Expired,
        ]);

        QuoteActivity::logQuoteActivity($quote, 'expired', 'Angebot abgelaufen');

        return true;
    }

    /**
     * Create contract from accepted quote.
     */
    private function createContractFromQuote(Quote $quote): Contract
    {
        $contract = Contract::create([
            'contract_number' => $this->numberService->generateContractNumber(),
            'quote_id' => $quote->id,
            'type' => $quote->type,
            'client_name' => $quote->client_name,
            'client_company' => $quote->client_company,
            'client_email' => $quote->client_email,
            'client_phone' => $quote->client_phone,
            'client_address' => $quote->client_address,
            'title' => $quote->title,
            'subject' => $quote->subject,
            'terms_text' => $quote->terms_text,
            'subtotal' => $quote->subtotal,
            'tax_rate' => $quote->tax_rate,
            'tax_amount' => $quote->tax_amount,
            'total' => $quote->total,
            'billing_cycle' => $quote->billing_cycle,
            'min_term_months' => $quote->min_term_months,
            'auto_renewal' => $quote->auto_renewal,
            'notice_period_days' => $quote->notice_period_days ?? 30,
            'start_date' => $quote->contract_start_date ?? now()->toDateString(),
            'min_term_end_date' => $quote->min_term_months
                ? now()->addMonths($quote->min_term_months)->toDateString()
                : null,
            'current_period_start' => now()->toDateString(),
            'current_period_end' => $quote->billing_cycle
                ? now()->addMonths($quote->billing_cycle->getMonths())->toDateString()
                : null,
            'next_billing_date' => $quote->billing_cycle
                ? now()->toDateString()
                : null,
            'status' => ContractStatus::Active,
            'accepted_name' => $quote->accepted_name,
            'accepted_at' => $quote->accepted_at,
            'accepted_ip' => $quote->accepted_ip,
        ]);

        // Copy selected items to contract
        foreach ($quote->getSelectedItems() as $item) {
            ContractItem::create([
                'contract_id' => $contract->id,
                'service_id' => $item->service_id,
                'name' => $item->name,
                'description' => $item->description,
                'detailed_terms' => $item->detailed_terms,
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
                'recurring_price' => $quote->isRecurring() ? $item->total_price : null,
                'sort_order' => $item->sort_order,
            ]);
        }

        QuoteActivity::logContractActivity($contract, 'created', 'Vertrag aus Angebot erstellt');

        return $contract;
    }

    /**
     * Duplicate a quote.
     */
    public function duplicate(Quote $quote): Quote
    {
        $newQuote = $quote->replicate([
            'quote_number',
            'status',
            'sent_at',
            'first_viewed_at',
            'accepted_at',
            'accepted_name',
            'accepted_ip',
            'token',
            'reminder_count',
            'last_reminder_at',
        ]);

        $newQuote->quote_number = $this->numberService->generateQuoteNumber();
        $newQuote->status = QuoteStatus::Draft;
        $newQuote->valid_until = now()->addDays($this->settings->default_validity_days);
        $newQuote->save();

        // Duplicate items
        foreach ($quote->items as $item) {
            $newItem = $item->replicate();
            $newItem->quote_id = $newQuote->id;
            $newItem->save();
        }

        QuoteActivity::logQuoteActivity($newQuote, 'created', "Angebot dupliziert von #{$quote->quote_number}");

        return $newQuote;
    }

    /**
     * Update optional item selection.
     */
    public function updateOptionSelection(Quote $quote, int $itemId, bool $isSelected): void
    {
        $item = $quote->items()->find($itemId);

        if ($item && $item->is_optional) {
            $item->update(['is_selected' => $isSelected]);
            $quote->calculateTotals();
            $quote->save();

            QuoteActivity::logQuoteActivity(
                $quote,
                'options_updated',
                'Optionale Position '.($isSelected ? 'hinzugefügt' : 'entfernt').": {$item->name}",
                ['item_id' => $itemId, 'is_selected' => $isSelected],
                null
            );
        }
    }

    /**
     * Check if quote has any items with detailed terms.
     */
    public function hasServiceTerms(Quote $quote): bool
    {
        return $quote->items
            ->filter(fn ($item) => $item->hasDetailedTerms() && (! $item->is_optional || $item->is_selected))
            ->isNotEmpty();
    }

    /**
     * Generate PDF for service terms.
     *
     * @return string|null The PDF content, or null if no terms exist
     */
    public function generateServiceTermsPdf(Quote $quote): ?string
    {
        if (! $this->hasServiceTerms($quote)) {
            return null;
        }

        $pdf = Pdf::loadView('pdfs.service-terms', [
            'quote' => $quote->load('items'),
        ]);

        return $pdf->output();
    }
}
