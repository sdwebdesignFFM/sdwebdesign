<?php

namespace App\Models;

use App\Enums\BillingCycle;
use App\Enums\QuoteStatus;
use App\Enums\ServiceType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_number',
        'template_id',
        'created_by',
        'type',
        'client_name',
        'client_company',
        'client_email',
        'client_phone',
        'client_address',
        'title',
        'subject',
        'intro_text',
        'terms_text',
        'footer_text',
        'internal_notes',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'billing_cycle',
        'min_term_months',
        'auto_renewal',
        'notice_period_days',
        'contract_start_date',
        'status',
        'valid_until',
        'sent_at',
        'first_viewed_at',
        'accepted_at',
        'accepted_name',
        'accepted_ip',
        'accepted_user_agent',
        'accepted_documents',
        'document_hash',
        'billing_company',
        'billing_name',
        'billing_street',
        'billing_zip',
        'billing_city',
        'billing_country',
        'billing_vat_id',
        'signature_data',
        'signature_at',
        'admin_signature_data',
        'admin_signature_name',
        'admin_signature_position',
        'admin_signed_at',
        'customer_id',
        'client_id',
        'token',
        'reminder_count',
        'last_reminder_at',
        'requires_manual_review',
    ];

    protected function casts(): array
    {
        return [
            'type' => ServiceType::class,
            'billing_cycle' => BillingCycle::class,
            'status' => QuoteStatus::class,
            'subtotal' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'auto_renewal' => 'boolean',
            'contract_start_date' => 'date',
            'valid_until' => 'date',
            'sent_at' => 'datetime',
            'first_viewed_at' => 'datetime',
            'accepted_at' => 'datetime',
            'accepted_documents' => 'array',
            'signature_at' => 'datetime',
            'admin_signed_at' => 'datetime',
            'last_reminder_at' => 'datetime',
            'requires_manual_review' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Quote $quote) {
            if (empty($quote->token)) {
                $quote->token = Str::random(64);
            }

            $quote->subtotal = $quote->subtotal ?? 0;
            $quote->tax_amount = $quote->tax_amount ?? 0;
            $quote->total = $quote->total ?? 0;
        });
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(QuoteTemplate::class, 'template_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class)->orderBy('sort_order');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(QuoteActivity::class)->orderByDesc('created_at');
    }

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function hasBillingDetails(): bool
    {
        return ! empty($this->billing_name) && ! empty($this->billing_street) && ! empty($this->billing_city);
    }

    public function hasSignature(): bool
    {
        return ! empty($this->signature_data);
    }

    public function hasAdminSignature(): bool
    {
        return ! empty($this->admin_signature_data);
    }

    public function isFullySigned(): bool
    {
        return $this->hasSignature() && $this->hasAdminSignature();
    }

    /**
     * Automatically apply admin counter-signature from settings.
     *
     * @return bool True if counter-signed successfully, false otherwise
     */
    public function autoCounterSign(): bool
    {
        // Skip if manual review is required
        if ($this->requires_manual_review) {
            return false;
        }

        $settings = Setting::instance();

        if (! $settings->hasAdminSignature()) {
            return false;
        }

        $this->update([
            'admin_signature_data' => $settings->admin_signature_data,
            'admin_signature_name' => $settings->admin_signer_name,
            'admin_signature_position' => $settings->admin_signer_position,
            'admin_signed_at' => now(),
        ]);

        return true;
    }

    /**
     * Generate a unique hash of the quote document for legal verification.
     *
     * This hash represents the quote state at acceptance time and can be used
     * to verify document integrity in case of disputes.
     */
    public function generateDocumentHash(): string
    {
        $data = [
            'quote_number' => $this->quote_number,
            'version' => $this->updated_at->toIso8601String(),
            'title' => $this->title,
            'total' => $this->total,
            'terms_text' => $this->terms_text,
            'items' => $this->items->map(fn ($item) => [
                'name' => $item->name,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
                'detailed_terms' => $item->detailed_terms,
                'is_selected' => $item->is_selected,
            ])->toArray(),
        ];

        return hash('sha256', json_encode($data));
    }

    /**
     * Build the accepted documents metadata for legal proof.
     *
     * @return array{agb: array{accepted: bool, version: string}, items: array<int, array{id: int, name: string, has_terms: bool}>}
     */
    public function buildAcceptedDocuments(): array
    {
        return [
            'agb' => [
                'accepted' => true,
                'version' => $this->updated_at->toIso8601String(),
            ],
            'items' => $this->getSelectedItems()->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'has_terms' => $item->hasDetailedTerms(),
            ])->toArray(),
        ];
    }

    public function getBillingAddress(): string
    {
        $parts = array_filter([
            $this->billing_company,
            $this->billing_name,
            $this->billing_street,
            trim($this->billing_zip.' '.$this->billing_city),
            $this->billing_country !== 'Deutschland' ? $this->billing_country : null,
        ]);

        return implode("\n", $parts);
    }

    // Scopes
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', QuoteStatus::Draft);
    }

    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', QuoteStatus::Sent);
    }

    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', QuoteStatus::Accepted);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereIn('status', [
            QuoteStatus::Draft,
            QuoteStatus::Sent,
            QuoteStatus::Viewed,
        ]);
    }

    public function scopeExpiringSoon(Builder $query, int $days = 7): Builder
    {
        return $query->whereIn('status', [QuoteStatus::Sent, QuoteStatus::Viewed])
            ->whereNotNull('valid_until')
            ->whereBetween('valid_until', [now(), now()->addDays($days)]);
    }

    // Helpers
    public function canBeAccepted(): bool
    {
        if (! $this->status->isPending()) {
            return false;
        }

        if ($this->valid_until && $this->valid_until->isPast()) {
            return false;
        }

        return true;
    }

    public function isRecurring(): bool
    {
        return $this->type === ServiceType::Recurring;
    }

    public function isExpired(): bool
    {
        return $this->status === QuoteStatus::Expired
            || ($this->valid_until && $this->valid_until->isPast());
    }

    public function getSignedUrl(): string
    {
        return route('quotes.show', ['token' => $this->token]);
    }

    public function calculateTotals(): void
    {
        $subtotal = $this->items()
            ->where(function ($query) {
                $query->where('is_optional', false)
                    ->orWhere(function ($q) {
                        $q->where('is_optional', true)
                            ->where('is_selected', true);
                    });
            })
            ->sum('total_price');

        $this->subtotal = $subtotal;
        $this->tax_amount = round($subtotal * ($this->tax_rate / 100), 2);
        $this->total = $subtotal + $this->tax_amount;
    }

    public function markAsViewed(): void
    {
        if ($this->status === QuoteStatus::Sent && ! $this->first_viewed_at) {
            $this->update([
                'status' => QuoteStatus::Viewed,
                'first_viewed_at' => now(),
            ]);
        }
    }

    public function getSelectedItems()
    {
        return $this->items()
            ->where(function ($query) {
                $query->where('is_optional', false)
                    ->orWhere(function ($q) {
                        $q->where('is_optional', true)
                            ->where('is_selected', true);
                    });
            })
            ->get();
    }

    public function getClientFullName(): string
    {
        if ($this->client_company) {
            return $this->client_company.' ('.$this->client_name.')';
        }

        return $this->client_name;
    }

    public function getGreeting(): string
    {
        // Try to get salutation from linked client
        if ($this->client) {
            $salutation = $this->client->salutation;
            $lastName = $this->client->last_name;

            if ($salutation && $lastName) {
                return match ($salutation) {
                    'Frau' => "Sehr geehrte Frau {$lastName},",
                    'Herr' => "Sehr geehrter Herr {$lastName},",
                    default => "Guten Tag {$this->client->full_name},",
                };
            }

            if ($this->client->full_name) {
                return "Guten Tag {$this->client->full_name},";
            }
        }

        // Fallback to client_name on quote
        if ($this->client_name) {
            return "Guten Tag {$this->client_name},";
        }

        return 'Sehr geehrte Damen und Herren,';
    }
}
