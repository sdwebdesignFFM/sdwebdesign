<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id',
        'title',
        'description',
        'estimated_minutes',
        'due_date',
        'priority',
        'status',
        'is_recurring',
        'recurrence_rule',
        'next_reminder_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'estimated_minutes' => 'integer',
            'due_date' => 'date',
            'priority' => TaskPriority::class,
            'status' => TaskStatus::class,
            'is_recurring' => 'boolean',
            'recurrence_rule' => 'array',
            'next_reminder_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // Relationships

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function workLogs(): HasMany
    {
        return $this->hasMany(WorkLog::class)->orderByDesc('worked_on');
    }

    // Scopes

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereIn('status', [TaskStatus::Pending, TaskStatus::InProgress]);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', TaskStatus::Pending);
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', TaskStatus::InProgress);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', TaskStatus::Completed);
    }

    public function scopeForClient(Builder $query, int $clientId): Builder
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeDueSoon(Builder $query, int $days = 7): Builder
    {
        return $query->whereNotNull('due_date')
            ->where('due_date', '<=', now()->addDays($days))
            ->where('due_date', '>=', now());
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereNotNull('due_date')
            ->where('due_date', '<', now()->startOfDay())
            ->open();
    }

    public function scopeDueToday(Builder $query): Builder
    {
        return $query->whereNotNull('due_date')
            ->whereDate('due_date', now()->toDateString())
            ->open();
    }

    public function scopeRecurring(Builder $query): Builder
    {
        return $query->where('is_recurring', true);
    }

    // Computed Attributes

    /**
     * Get total logged time in minutes.
     */
    public function getTotalLoggedMinutesAttribute(): int
    {
        return $this->workLogs()->sum('duration_minutes');
    }

    /**
     * Get total logged time formatted as H:MM.
     */
    public function getTotalLoggedFormattedAttribute(): string
    {
        $minutes = $this->total_logged_minutes;
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        return sprintf('%d:%02d', $hours, $mins);
    }

    /**
     * Get estimated time formatted as H:MM.
     */
    public function getEstimatedFormattedAttribute(): ?string
    {
        if ($this->estimated_minutes === null) {
            return null;
        }

        $hours = floor($this->estimated_minutes / 60);
        $mins = $this->estimated_minutes % 60;

        return sprintf('%d:%02d', $hours, $mins);
    }

    /**
     * Get remaining time in minutes (estimated - logged).
     */
    public function getRemainingMinutesAttribute(): ?int
    {
        if ($this->estimated_minutes === null) {
            return null;
        }

        return max(0, $this->estimated_minutes - $this->total_logged_minutes);
    }

    /**
     * Get progress percentage (0-100).
     */
    public function getProgressPercentageAttribute(): ?int
    {
        if ($this->estimated_minutes === null || $this->estimated_minutes === 0) {
            return null;
        }

        $percentage = ($this->total_logged_minutes / $this->estimated_minutes) * 100;

        return min(100, (int) round($percentage));
    }

    // Helper Methods

    /**
     * Check if task is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date !== null
            && $this->due_date->lt(now()->startOfDay())
            && $this->status->isOpen();
    }

    /**
     * Check if task is due today.
     */
    public function isDueToday(): bool
    {
        return $this->due_date !== null
            && $this->due_date->isToday()
            && $this->status->isOpen();
    }

    /**
     * Check if task is due this week.
     */
    public function isDueThisWeek(): bool
    {
        return $this->due_date !== null
            && $this->due_date->isBetween(now(), now()->endOfWeek())
            && $this->status->isOpen();
    }

    /**
     * Mark task as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => TaskStatus::Completed,
            'completed_at' => now(),
        ]);

        // Create next occurrence for recurring tasks
        if ($this->is_recurring && $this->recurrence_rule) {
            $this->createNextOccurrence();
        }
    }

    /**
     * Mark task as in progress.
     */
    public function markAsInProgress(): void
    {
        if ($this->status === TaskStatus::Pending) {
            $this->update(['status' => TaskStatus::InProgress]);
        }
    }

    /**
     * Create next occurrence for recurring task.
     */
    public function createNextOccurrence(): ?Task
    {
        if (! $this->is_recurring || ! $this->recurrence_rule) {
            return null;
        }

        $rule = $this->recurrence_rule;
        $interval = $rule['interval'] ?? 'weekly';
        $every = $rule['every'] ?? 1;

        $nextDueDate = match ($interval) {
            'daily' => $this->due_date?->addDays($every),
            'weekly' => $this->due_date?->addWeeks($every),
            'monthly' => $this->due_date?->addMonths($every),
            default => $this->due_date?->addWeeks($every),
        };

        return static::create([
            'client_id' => $this->client_id,
            'title' => $this->title,
            'description' => $this->description,
            'estimated_minutes' => $this->estimated_minutes,
            'due_date' => $nextDueDate,
            'priority' => $this->priority,
            'status' => TaskStatus::Pending,
            'is_recurring' => true,
            'recurrence_rule' => $this->recurrence_rule,
        ]);
    }

    /**
     * Get count of open tasks that are overdue or due today.
     */
    public static function getUrgentCount(): int
    {
        return static::open()
            ->where(function ($query) {
                $query->overdue()
                    ->orWhere(function ($q) {
                        $q->dueToday();
                    });
            })
            ->count();
    }

    /**
     * Get duration options for select (same as WorkLog).
     */
    public static function getDurationOptions(): array
    {
        return WorkLog::getDurationOptions();
    }
}
