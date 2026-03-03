<?php

namespace App\Filament\Pages;

use App\Models\Client;
use App\Models\WorkLog;
use App\Services\WorkLog\WorkLogService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;

class WorkLogBilling extends Page
{
    protected string $view = 'filament.pages.work-log-billing';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static \UnitEnum|string|null $navigationGroup = 'Zeiterfassung';

    protected static ?int $navigationSort = 2;

    #[Url]
    public ?string $filterMonth = null;

    public ?int $selectedClientId = null;

    public ?string $selectedMonth = null;

    public array $selectedWorkLogIds = [];

    public static function getNavigationLabel(): string
    {
        return 'Abrechnung';
    }

    public function getTitle(): string
    {
        return 'Arbeitszeiten abrechnen';
    }

    public function getUnbilledSummary(): Collection
    {
        $summary = app(WorkLogService::class)->getUnbilledSummary();

        if ($this->filterMonth) {
            $summary = $summary->filter(
                fn ($item) => $item['month']->format('Y-m') === $this->filterMonth
            )->values();
        }

        return $summary;
    }

    /**
     * @return array<string, string>
     */
    public function getFilterMonthOptions(): array
    {
        $months = ['' => 'Alle Monate'];
        for ($i = 0; $i < 12; $i++) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $months[$key] = $date->translatedFormat('F Y');
        }

        return $months;
    }

    public function getSelectedEntries(): Collection
    {
        if (! $this->selectedClientId || ! $this->selectedMonth) {
            return collect();
        }

        $month = Carbon::parse($this->selectedMonth);

        return app(WorkLogService::class)->getEntriesForBilling(
            Client::find($this->selectedClientId),
            $month
        );
    }

    public function getSelectedClient(): ?Client
    {
        return $this->selectedClientId ? Client::find($this->selectedClientId) : null;
    }

    public function getSelectedMonthCarbon(): ?Carbon
    {
        return $this->selectedMonth ? Carbon::parse($this->selectedMonth) : null;
    }

    public function getBillingTotals(): array
    {
        $entries = $this->getSelectedEntries();

        if ($entries->isEmpty()) {
            return [
                'total_minutes' => 0,
                'total_hours' => '0:00',
                'hourly_rate' => 0,
                'subtotal' => 0,
                'tax_rate' => 19,
                'tax_amount' => 0,
                'total' => 0,
            ];
        }

        // Filter to only selected entries
        $selectedEntries = $entries->filter(fn ($entry) => in_array($entry->id, $this->selectedWorkLogIds));

        if ($selectedEntries->isEmpty()) {
            $selectedEntries = $entries;
            $this->selectedWorkLogIds = $entries->pluck('id')->toArray();
        }

        return app(WorkLogService::class)->calculateBillingTotals($selectedEntries);
    }

    public function selectGroup(int $clientId, string $month): void
    {
        $this->selectedClientId = $clientId;
        $this->selectedMonth = $month;

        // Select all entries by default
        $entries = $this->getSelectedEntries();
        $this->selectedWorkLogIds = $entries->pluck('id')->toArray();
    }

    public function clearSelection(): void
    {
        $this->selectedClientId = null;
        $this->selectedMonth = null;
        $this->selectedWorkLogIds = [];
    }

    public function toggleEntry(int $workLogId): void
    {
        if (in_array($workLogId, $this->selectedWorkLogIds)) {
            $this->selectedWorkLogIds = array_values(array_diff($this->selectedWorkLogIds, [$workLogId]));
        } else {
            $this->selectedWorkLogIds[] = $workLogId;
        }
    }

    public function selectAllEntries(): void
    {
        $this->selectedWorkLogIds = $this->getSelectedEntries()->pluck('id')->toArray();
    }

    public function deselectAllEntries(): void
    {
        $this->selectedWorkLogIds = [];
    }

    public function markAsBilled(): void
    {
        if (empty($this->selectedWorkLogIds)) {
            Notification::make()
                ->title('Keine Einträge ausgewählt')
                ->body('Bitte wählen Sie mindestens einen Eintrag aus.')
                ->warning()
                ->send();

            return;
        }

        $count = app(WorkLogService::class)->markAsBilled($this->selectedWorkLogIds);

        Notification::make()
            ->title('Als abgerechnet markiert')
            ->body("{$count} Einträge wurden als abgerechnet markiert.")
            ->success()
            ->send();

        $this->clearSelection();
    }

    public function createInvoice(): void
    {
        if (empty($this->selectedWorkLogIds)) {
            Notification::make()
                ->title('Keine Einträge ausgewählt')
                ->body('Bitte wählen Sie mindestens einen Eintrag zum Abrechnen aus.')
                ->warning()
                ->send();

            return;
        }

        $client = $this->getSelectedClient();
        $month = $this->getSelectedMonthCarbon();

        if (! $client || ! $month) {
            Notification::make()
                ->title('Fehler')
                ->body('Kunde oder Monat nicht ausgewählt.')
                ->danger()
                ->send();

            return;
        }

        try {
            $invoice = app(WorkLogService::class)->createInvoice(
                $client,
                $month,
                $this->selectedWorkLogIds
            );

            Notification::make()
                ->title('Rechnung erstellt')
                ->body("Rechnung {$invoice->invoice_number} wurde erfolgreich erstellt.")
                ->success()
                ->send();

            // Clear selection and refresh
            $this->clearSelection();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Fehler beim Erstellen der Rechnung')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function formatMinutes(int $minutes): string
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        return sprintf('%d:%02d', $hours, $mins);
    }

    public function formatMoney(float $amount): string
    {
        return number_format($amount, 2, ',', '.').' €';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back_to_worklogs')
                ->label('Zurück zur Übersicht')
                ->url(route('filament.admin.resources.work-logs.index'))
                ->color('gray'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = WorkLog::unbilled()
            ->select('client_id')
            ->selectRaw("DATE_FORMAT(worked_on, '%Y-%m') as month")
            ->groupBy('client_id', 'month')
            ->get()
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
