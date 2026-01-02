<?php

namespace App\Services\Quote;

use App\Settings\QuoteSettings;
use Illuminate\Support\Facades\DB;

class QuoteNumberService
{
    public function __construct(
        private QuoteSettings $settings
    ) {}

    /**
     * Generate next quote number.
     * Format: A-26-01-1001 (Prefix-Year2-Month-Sequence)
     */
    public function generateQuoteNumber(): string
    {
        $prefix = $this->settings->quote_prefix;
        $year = now()->format('y');
        $month = now()->format('m');

        $number = $this->getNextSequenceForMonth('quote', $year, $month);

        return sprintf('%s-%s-%s-%d', $prefix, $year, $month, $number);
    }

    /**
     * Generate next contract number.
     * Format: V-26-01-1001 (Prefix-Year2-Month-Sequence)
     */
    public function generateContractNumber(): string
    {
        $prefix = $this->settings->contract_prefix;
        $year = now()->format('y');
        $month = now()->format('m');

        $number = $this->getNextSequenceForMonth('contract', $year, $month);

        return sprintf('%s-%s-%s-%d', $prefix, $year, $month, $number);
    }

    /**
     * Generate next invoice number.
     * Format: R-26-01-1001 (Prefix-Year2-Month-Sequence)
     */
    public function generateInvoiceNumber(): string
    {
        $prefix = $this->settings->invoice_prefix;
        $year = now()->format('y');
        $month = now()->format('m');

        $number = $this->getNextSequenceForMonth('invoice', $year, $month);

        return sprintf('%s-%s-%s-%d', $prefix, $year, $month, $number);
    }

    /**
     * Generate next cancellation number.
     * Format: ST-26-01-1001 (Prefix-Year2-Month-Sequence)
     */
    public function generateCancellationNumber(): string
    {
        $prefix = $this->settings->cancellation_prefix;
        $year = now()->format('y');
        $month = now()->format('m');

        $number = $this->getNextSequenceForMonth('cancellation', $year, $month);

        return sprintf('%s-%s-%s-%d', $prefix, $year, $month, $number);
    }

    /**
     * Get next sequence number for a month.
     * Automatically resets at month change.
     * Thread-safe through DB locking.
     */
    private function getNextSequenceForMonth(string $type, string $year, string $month): int
    {
        $currentPeriodKey = "{$type}_current_period";
        $sequenceKey = "{$type}_sequence";
        $currentPeriod = "{$year}-{$month}";
        $startNumber = match ($type) {
            'quote' => $this->settings->quote_start_number,
            'contract' => $this->settings->contract_start_number,
            'invoice' => $this->settings->invoice_start_number,
            'cancellation' => $this->settings->cancellation_start_number,
            default => 1001,
        };

        return DB::transaction(function () use ($currentPeriodKey, $sequenceKey, $currentPeriod, $startNumber) {
            // Lock the period row for thread safety
            $periodRow = DB::table('spatie_settings')
                ->where('group', 'quote')
                ->where('name', $currentPeriodKey)
                ->lockForUpdate()
                ->first();

            $sequenceRow = DB::table('spatie_settings')
                ->where('group', 'quote')
                ->where('name', $sequenceKey)
                ->lockForUpdate()
                ->first();

            $storedPeriod = $periodRow ? json_decode($periodRow->payload, true) : '';

            // Check if month changed → reset
            if ($storedPeriod !== $currentPeriod) {
                // New month → reset sequence
                DB::table('spatie_settings')
                    ->where('group', 'quote')
                    ->where('name', $currentPeriodKey)
                    ->update(['payload' => json_encode($currentPeriod)]);

                DB::table('spatie_settings')
                    ->where('group', 'quote')
                    ->where('name', $sequenceKey)
                    ->update(['payload' => json_encode($startNumber)]);

                // Refresh cached settings
                $this->settings->refresh();

                return $startNumber;
            }

            // Same month → increment sequence
            $currentSequence = $sequenceRow ? json_decode($sequenceRow->payload, true) : $startNumber;
            $nextNumber = (int) $currentSequence + 1;

            DB::table('spatie_settings')
                ->where('group', 'quote')
                ->where('name', $sequenceKey)
                ->update(['payload' => json_encode($nextNumber)]);

            // Refresh cached settings
            $this->settings->refresh();

            return $nextNumber;
        });
    }

    /**
     * Preview next quote number (without incrementing).
     */
    public function previewNextQuoteNumber(): string
    {
        return $this->previewNextNumber('quote');
    }

    /**
     * Preview next contract number (without incrementing).
     */
    public function previewNextContractNumber(): string
    {
        return $this->previewNextNumber('contract');
    }

    /**
     * Preview next invoice number (without incrementing).
     */
    public function previewNextInvoiceNumber(): string
    {
        return $this->previewNextNumber('invoice');
    }

    /**
     * Preview next cancellation number (without incrementing).
     */
    public function previewNextCancellationNumber(): string
    {
        return $this->previewNextNumber('cancellation');
    }

    private function previewNextNumber(string $type): string
    {
        $prefix = match ($type) {
            'quote' => $this->settings->quote_prefix,
            'contract' => $this->settings->contract_prefix,
            'invoice' => $this->settings->invoice_prefix,
            'cancellation' => $this->settings->cancellation_prefix,
            default => 'X',
        };
        $year = now()->format('y');
        $month = now()->format('m');
        $currentPeriod = "{$year}-{$month}";

        $storedPeriod = match ($type) {
            'quote' => $this->settings->quote_current_period,
            'contract' => $this->settings->contract_current_period,
            'invoice' => $this->settings->invoice_current_period,
            'cancellation' => $this->settings->cancellation_current_period,
            default => '',
        };

        $startNumber = match ($type) {
            'quote' => $this->settings->quote_start_number,
            'contract' => $this->settings->contract_start_number,
            'invoice' => $this->settings->invoice_start_number,
            'cancellation' => $this->settings->cancellation_start_number,
            default => 1001,
        };

        $currentSequence = match ($type) {
            'quote' => $this->settings->quote_sequence,
            'contract' => $this->settings->contract_sequence,
            'invoice' => $this->settings->invoice_sequence,
            'cancellation' => $this->settings->cancellation_sequence,
            default => 1000,
        };

        if ($storedPeriod !== $currentPeriod) {
            // Would be reset
            $nextNumber = $startNumber;
        } else {
            $nextNumber = $currentSequence + 1;
        }

        return sprintf('%s-%s-%s-%d', $prefix, $year, $month, $nextNumber);
    }

    /**
     * Get current sequence info for admin display.
     */
    public function getCurrentQuoteSequence(): array
    {
        return [
            'period' => $this->settings->quote_current_period ?: now()->format('y-m'),
            'sequence' => $this->settings->quote_sequence,
            'next_number' => $this->previewNextQuoteNumber(),
        ];
    }

    public function getCurrentContractSequence(): array
    {
        return [
            'period' => $this->settings->contract_current_period ?: now()->format('y-m'),
            'sequence' => $this->settings->contract_sequence,
            'next_number' => $this->previewNextContractNumber(),
        ];
    }

    public function getCurrentInvoiceSequence(): array
    {
        return [
            'period' => $this->settings->invoice_current_period ?: now()->format('y-m'),
            'sequence' => $this->settings->invoice_sequence,
            'next_number' => $this->previewNextInvoiceNumber(),
        ];
    }
}
