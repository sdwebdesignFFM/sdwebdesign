<?php

namespace App\Mail;

use App\Models\Quote;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Quote $quote
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ihr Angebot: '.$this->quote->title.' ('.$this->quote->quote_number.')',
        );
    }

    public function content(): Content
    {
        $settings = Setting::instance();

        return new Content(
            view: 'emails.quotes.notification',
            with: [
                'quote' => $this->quote,
                'settings' => $settings,
                'quoteUrl' => route('quotes.show', ['token' => $this->quote->token]),
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
