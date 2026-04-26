<?php

namespace App\Livewire;

use App\Mail\WhitepaperDelivery;
use App\Models\WhitepaperLead;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Validate;
use Livewire\Component;

class WhitepaperRequestForm extends Component
{
    public string $whitepaperSlug = '';

    public string $whitepaperTitle = '';

    public string $pdfView = '';

    public string $pdfFilename = 'whitepaper.pdf';

    #[Validate('required|email:rfc|max:191')]
    public string $email = '';

    #[Validate('nullable|string|max:120')]
    public string $name = '';

    #[Validate('nullable|string|max:120')]
    public string $company = '';

    #[Validate('nullable|string|max:120')]
    public string $role = '';

    #[Validate('accepted')]
    public bool $consent = false;

    public bool $newsletter_opt_in = false;

    public bool $submitted = false;

    public function mount(string $whitepaperSlug, string $whitepaperTitle, string $pdfView, string $pdfFilename): void
    {
        $this->whitepaperSlug = $whitepaperSlug;
        $this->whitepaperTitle = $whitepaperTitle;
        $this->pdfView = $pdfView;
        $this->pdfFilename = $pdfFilename;
    }

    public function submit(): void
    {
        $this->validate();

        $rateLimitKey = 'whitepaper-request:'.request()->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $this->addError('email', 'Zu viele Anfragen. Bitte versuchen Sie es in einer Stunde erneut.');

            return;
        }
        RateLimiter::hit($rateLimitKey, 3600);

        $lead = WhitepaperLead::updateOrCreate(
            [
                'whitepaper_slug' => $this->whitepaperSlug,
                'email' => $this->email,
            ],
            [
                'name' => $this->name ?: null,
                'company' => $this->company ?: null,
                'role' => $this->role ?: null,
                'locale' => app()->getLocale(),
                'ip' => request()->ip(),
                'user_agent' => substr((string) request()->userAgent(), 0, 191),
                'newsletter_opt_in' => $this->newsletter_opt_in,
            ]
        );

        Mail::to($lead->email)->send(new WhitepaperDelivery(
            lead: $lead,
            whitepaperTitle: $this->whitepaperTitle,
            pdfView: $this->pdfView,
            pdfFilename: $this->pdfFilename,
        ));

        $lead->update(['sent_at' => now()]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.whitepaper-request-form');
    }
}
