<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteActivity extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'quote_id',
        'contract_id',
        'invoice_id',
        'user_id',
        'action',
        'description',
        'metadata',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (QuoteActivity $activity) {
            $activity->created_at = $activity->created_at ?? now();
        });
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function logQuoteActivity(
        Quote $quote,
        string $action,
        ?string $description = null,
        ?array $metadata = null,
        ?int $userId = null
    ): self {
        return self::create([
            'quote_id' => $quote->id,
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function logContractActivity(
        Contract $contract,
        string $action,
        ?string $description = null,
        ?array $metadata = null,
        ?int $userId = null
    ): self {
        return self::create([
            'contract_id' => $contract->id,
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function logInvoiceActivity(
        Invoice $invoice,
        string $action,
        ?string $description = null,
        ?array $metadata = null,
        ?int $userId = null
    ): self {
        return self::create([
            'invoice_id' => $invoice->id,
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function getActionLabel(): string
    {
        return match ($this->action) {
            'created' => 'Erstellt',
            'updated' => 'Aktualisiert',
            'sent' => 'Gesendet',
            'viewed' => 'Angesehen',
            'accepted' => 'Angenommen',
            'declined' => 'Abgelehnt',
            'expired' => 'Abgelaufen',
            'reminder_sent' => 'Erinnerung gesendet',
            'options_updated' => 'Optionen geändert',
            'paid' => 'Bezahlt',
            'cancelled' => 'Storniert',
            'renewed' => 'Verlängert',
            default => $this->action,
        };
    }
}
