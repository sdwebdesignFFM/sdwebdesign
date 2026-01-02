<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Quote number settings
        $this->migrator->add('quote.quote_prefix', 'A');
        $this->migrator->add('quote.quote_start_number', 1001);
        $this->migrator->add('quote.quote_sequence', 1000);
        $this->migrator->add('quote.quote_current_period', '');

        // Contract number settings
        $this->migrator->add('quote.contract_prefix', 'V');
        $this->migrator->add('quote.contract_start_number', 1001);
        $this->migrator->add('quote.contract_sequence', 1000);
        $this->migrator->add('quote.contract_current_period', '');

        // Invoice number settings
        $this->migrator->add('quote.invoice_prefix', 'R');
        $this->migrator->add('quote.invoice_start_number', 1001);
        $this->migrator->add('quote.invoice_sequence', 1000);
        $this->migrator->add('quote.invoice_current_period', '');

        // Cancellation number settings
        $this->migrator->add('quote.cancellation_prefix', 'ST');
        $this->migrator->add('quote.cancellation_start_number', 1001);
        $this->migrator->add('quote.cancellation_sequence', 1000);
        $this->migrator->add('quote.cancellation_current_period', '');

        // Default settings
        $this->migrator->add('quote.default_validity_days', 30);
        $this->migrator->add('quote.default_payment_terms_days', 14);
        $this->migrator->add('quote.default_tax_rate', 19.00);
        $this->migrator->add('quote.reminder_days_before_expiry', 7);
    }
};
