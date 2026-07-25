<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactRequestConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array{
     *     name: string,
     *     email: string,
     *     company: string,
     *     phone: string,
     *     projectTypes: array<string>,
     *     budget: string,
     *     timeline: string,
     *     projectDescription: string
     * } $data
     */
    public function __construct(
        public array $data
    ) {}

    public function envelope(): Envelope
    {
        $settings = Setting::instance();

        return new Envelope(
            subject: 'Ihre Anfrage bei '.($settings->company_name ?? 'sdwebdesign'),
        );
    }

    public function content(): Content
    {
        $settings = Setting::instance();

        return new Content(
            view: 'emails.contact-request-confirmation',
            with: [
                'data' => $this->data,
                'settings' => $settings,
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
