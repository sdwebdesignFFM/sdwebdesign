<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactRequestAdmin extends Mailable
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
        return new Envelope(
            subject: 'Neue Projektanfrage: ' . $this->data['name'],
            replyTo: $this->data['email'],
        );
    }

    public function content(): Content
    {
        $settings = Setting::instance();

        return new Content(
            view: 'emails.contact-request-admin',
            with: [
                'data' => $this->data,
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
