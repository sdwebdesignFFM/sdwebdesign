<?php

namespace App\Services\WorkLog;

use App\Enums\InvoiceStatus;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\QuoteActivity;
use App\Models\WorkLog;
use App\Services\Quote\QuoteNumberService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WorkLogService
{
    public function __construct(
        private QuoteNumberService $numberService
    ) {}

    /**
     * Get unbilled entries grouped by client and month.
     *
     * @return Collection<int, array{client: Client, month: Carbon, entries: Collection, total_minutes: int, total_amount: float}>
     */
    public function getUnbilledSummary(): Collection
    {
        $entries = WorkLog::with('client')
            ->unbilled()
            ->orderBy('worked_on', 'desc')
            ->get();

        return $entries
            ->groupBy(fn (WorkLog $log) => $log->client_id.'_'.$log->worked_on->format('Y-m'))
            ->map(function (Collection $group) {
                $first = $group->first();
                $totalMinutes = $group->sum('duration_minutes');
                $totalAmount = $group->sum(fn (WorkLog $log) => $log->total_amount);

                return [
                    'client' => $first->client,
                    'month' => $first->worked_on->startOfMonth(),
                    'entries' => $group,
                    'total_minutes' => $totalMinutes,
                    'total_amount' => $totalAmount,
                ];
            })
            ->sortByDesc(fn ($item) => $item['month']->format('Y-m'))
            ->values();
    }

    /**
     * Get unbilled entries for a specific client and month.
     */
    public function getEntriesForBilling(Client $client, Carbon $month): Collection
    {
        return WorkLog::forClient($client->id)
            ->forMonth($month->year, $month->month)
            ->unbilled()
            ->with('task')
            ->orderBy('worked_on')
            ->get();
    }

    /**
     * Calculate totals for billing entries.
     *
     * @return array{total_minutes: int, total_hours: string, hourly_rate: float, subtotal: float, tax_rate: float, tax_amount: float, total: float}
     */
    public function calculateBillingTotals(Collection $workLogs, ?float $overrideHourlyRate = null): array
    {
        $totalMinutes = $workLogs->sum('duration_minutes');
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        // Use override or get from first work log's effective rate
        $hourlyRate = $overrideHourlyRate ?? ($workLogs->first()?->effective_hourly_rate ?? 85.00);
        $subtotal = round(($totalMinutes / 60) * $hourlyRate, 2);
        $taxRate = 19.00;
        $taxAmount = round($subtotal * ($taxRate / 100), 2);
        $total = round($subtotal + $taxAmount, 2);

        return [
            'total_minutes' => $totalMinutes,
            'total_hours' => sprintf('%d:%02d', $hours, $minutes),
            'hourly_rate' => $hourlyRate,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total' => $total,
        ];
    }

    /**
     * Create an invoice from work logs.
     *
     * @param  array<int>  $workLogIds
     */
    public function createInvoice(Client $client, Carbon $month, array $workLogIds): Invoice
    {
        return DB::transaction(function () use ($client, $month, $workLogIds) {
            $workLogs = WorkLog::whereIn('id', $workLogIds)
                ->where('client_id', $client->id)
                ->unbilled()
                ->orderBy('worked_on')
                ->get();

            if ($workLogs->isEmpty()) {
                throw new \InvalidArgumentException('Keine abrechenbaren Einträge gefunden.');
            }

            $totals = $this->calculateBillingTotals($workLogs);

            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $this->numberService->generateInvoiceNumber(),
                'client_name' => $client->full_name,
                'client_company' => $client->company,
                'client_email' => $client->email,
                'client_address' => $client->full_address,
                'subtotal' => $totals['subtotal'],
                'tax_rate' => $totals['tax_rate'],
                'tax_amount' => $totals['tax_amount'],
                'total' => $totals['total'],
                'period_start' => $month->copy()->startOfMonth(),
                'period_end' => $month->copy()->endOfMonth(),
                'status' => InvoiceStatus::Draft,
                'issue_date' => now(),
                'due_date' => now()->addDays(14),
            ]);

            // Create invoice items from work logs
            $sortOrder = 0;
            foreach ($workLogs as $workLog) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'name' => $workLog->worked_on->format('d.m.Y').' - '.$workLog->title,
                    'description' => $workLog->description,
                    'quantity' => $workLog->duration_hours,
                    'unit' => 'Std.',
                    'unit_price' => $workLog->effective_hourly_rate,
                    'total_price' => $workLog->total_amount,
                    'sort_order' => $sortOrder++,
                ]);

                // Mark as billed
                $workLog->markAsBilled($invoice);
            }

            // Log activity
            QuoteActivity::logInvoiceActivity(
                $invoice,
                'created',
                'Rechnung aus Zeiterfassung erstellt',
                [
                    'work_log_count' => $workLogs->count(),
                    'period' => $month->translatedFormat('F Y'),
                ]
            );

            return $invoice;
        });
    }

    /**
     * Get statistics for a client.
     *
     * @return array{total_hours_unbilled: float, total_amount_unbilled: float, total_hours_billed: float, total_amount_billed: float}
     */
    public function getClientStatistics(Client $client): array
    {
        $unbilledLogs = WorkLog::forClient($client->id)->unbilled()->get();
        $billedLogs = WorkLog::forClient($client->id)->billed()->get();

        return [
            'total_hours_unbilled' => round($unbilledLogs->sum('duration_minutes') / 60, 2),
            'total_amount_unbilled' => $unbilledLogs->sum(fn (WorkLog $log) => $log->total_amount),
            'total_hours_billed' => round($billedLogs->sum('duration_minutes') / 60, 2),
            'total_amount_billed' => $billedLogs->sum(fn (WorkLog $log) => $log->total_amount),
        ];
    }

    /**
     * Get monthly statistics.
     *
     * @return array{total_hours: float, total_amount: float, entries_count: int}
     */
    public function getMonthlyStatistics(int $year, int $month): array
    {
        $logs = WorkLog::forMonth($year, $month)->get();

        return [
            'total_hours' => round($logs->sum('duration_minutes') / 60, 2),
            'total_amount' => $logs->sum(fn (WorkLog $log) => $log->total_amount),
            'entries_count' => $logs->count(),
        ];
    }

    /**
     * Format minutes as hours string (H:MM).
     */
    public function formatMinutesAsHours(int $minutes): string
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        return sprintf('%d:%02d', $hours, $mins);
    }
}
