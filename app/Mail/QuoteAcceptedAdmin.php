<?php

namespace App\Mail;

use App\Models\Contract;
use App\Models\Quote;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteAcceptedAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Quote $quote,
        public Contract $contract
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Angebot angenommen: '.$this->quote->quote_number.' - '.$this->quote->client_name,
        );
    }

    public function content(): Content
    {
        $settings = Setting::instance();

        return new Content(
            view: 'emails.quotes.accepted-admin',
            with: [
                'quote' => $this->quote,
                'contract' => $this->contract,
                'settings' => $settings,
            ],
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
