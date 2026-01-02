<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class QuoteSettings extends Settings
{
    // Quote number settings
    public string $quote_prefix;

    public int $quote_start_number;

    public int $quote_sequence;

    public string $quote_current_period;

    // Contract number settings
    public string $contract_prefix;

    public int $contract_start_number;

    public int $contract_sequence;

    public string $contract_current_period;

    // Invoice number settings
    public string $invoice_prefix;

    public int $invoice_start_number;

    public int $invoice_sequence;

    public string $invoice_current_period;

    // Cancellation number settings
    public string $cancellation_prefix;

    public int $cancellation_start_number;

    public int $cancellation_sequence;

    public string $cancellation_current_period;

    // Default settings
    public int $default_validity_days;

    public int $default_payment_terms_days;

    public float $default_tax_rate;

    public int $reminder_days_before_expiry;

    public static function group(): string
    {
        return 'quote';
    }
}
