<?php

namespace App\Http\Controllers;

use App\Enums\QuoteStatus;
use App\Models\Quote;
use App\Services\Quote\PdfService;
use App\Services\Quote\QuoteService;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function __construct(
        private QuoteService $quoteService,
        private PdfService $pdfService
    ) {}

    /**
     * Display the quote for the client.
     */
    public function show(string $token)
    {
        $quote = Quote::where('token', $token)
            ->with(['items' => fn ($q) => $q->orderBy('sort_order')])
            ->firstOrFail();

        // Check if expired
        if ($quote->isExpired() && $quote->status !== QuoteStatus::Accepted) {
            return view('pages.quotes.expired', compact('quote'));
        }

        // Mark as viewed
        $this->quoteService->markAsViewed($quote);

        return view('pages.quotes.show', compact('quote'));
    }

    /**
     * Accept the quote (handled by Livewire component).
     */
    public function accept(Request $request, string $token)
    {
        $quote = Quote::where('token', $token)->firstOrFail();

        if (! $quote->canBeAccepted()) {
            return back()->with('error', 'Dieses Angebot kann nicht mehr angenommen werden.');
        }

        $validated = $request->validate([
            'accepted_name' => 'required|string|max:255',
            'terms_accepted' => 'required|accepted',
        ]);

        $contract = $this->quoteService->accept($quote, $validated['accepted_name']);

        if ($contract) {
            return redirect()->route('quotes.accepted', ['token' => $token]);
        }

        return back()->with('error', 'Es ist ein Fehler aufgetreten.');
    }

    /**
     * Show acceptance confirmation.
     */
    public function accepted(string $token)
    {
        $quote = Quote::where('token', $token)
            ->where('status', QuoteStatus::Accepted)
            ->with('contract')
            ->firstOrFail();

        return view('pages.quotes.accepted', compact('quote'));
    }

    /**
     * Download quote PDF.
     */
    public function pdf(string $token)
    {
        $quote = Quote::where('token', $token)->firstOrFail();

        return $this->pdfService->downloadQuote($quote);
    }

    /**
     * Update optional item selection (AJAX).
     */
    public function updateOptions(Request $request, string $token)
    {
        $quote = Quote::where('token', $token)->firstOrFail();

        if (! $quote->canBeAccepted()) {
            return response()->json(['error' => 'Angebot kann nicht mehr geändert werden.'], 403);
        }

        $validated = $request->validate([
            'item_id' => 'required|integer',
            'is_selected' => 'required|boolean',
        ]);

        $this->quoteService->updateOptionSelection(
            $quote,
            $validated['item_id'],
            $validated['is_selected']
        );

        $quote->refresh();

        return response()->json([
            'success' => true,
            'subtotal' => number_format($quote->subtotal, 2, ',', '.'),
            'tax_amount' => number_format($quote->tax_amount, 2, ',', '.'),
            'total' => number_format($quote->total, 2, ',', '.'),
        ]);
    }
}
