<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\SitemapGenerator;

/*
|--------------------------------------------------------------------------
| German Routes (Default - No Prefix)
|--------------------------------------------------------------------------
*/
Route::middleware('locale:de')->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('de.home');
    Route::get('/loesungen', [PageController::class, 'solutions'])->name('de.solutions');
    // Legacy route for flat solution details (backwards compatibility)
    Route::get('/loesungen/{slug}', [PageController::class, 'solutionDetail'])->name('de.solutions.show');
    Route::get('/referenzen', [PageController::class, 'references'])->name('de.references');
    Route::get('/referenzen/{slug}', [PageController::class, 'referenceDetail'])->name('de.references.show');
    Route::get('/ueber-uns', [PageController::class, 'about'])->name('de.about');
    Route::get('/kontakt', [PageController::class, 'contact'])->name('de.contact');
    Route::post('/kontakt', [PageController::class, 'contactSubmit'])->name('de.contact.submit');
    Route::get('/anfrage-gesendet', [PageController::class, 'contactThankYou'])->name('de.contact.thank-you');
    Route::get('/impressum', [PageController::class, 'imprint'])->name('de.imprint');
    Route::get('/datenschutz', [PageController::class, 'privacy'])->name('de.privacy');
    Route::get('/agb', [PageController::class, 'agb'])->name('de.agb');
    Route::get('/barrierefreiheit', [PageController::class, 'accessibility'])->name('de.accessibility');
    // Blog -> Ratgeber Redirect (301)
    Route::get('/blog', fn () => redirect('/ratgeber', 301))->name('de.blog');
    Route::get('/blog/{slug}', fn ($slug) => redirect("/ratgeber/{$slug}", 301))->name('de.blog.show');

    // Ratgeber (Guides)
    Route::get('/ratgeber', [PageController::class, 'guideOverview'])->name('de.guides');
    Route::get('/ratgeber/{slug}', [PageController::class, 'guide'])->name('de.guide.show');

    // SEO & SEA (new URLs)
    Route::get('/suchmaschinenoptimierung', [PageController::class, 'seo'])->name('de.seo');
    Route::get('/suchmaschinenwerbung', [PageController::class, 'sea'])->name('de.sea');
    // Legacy redirects (301)
    Route::get('/seo', fn () => redirect('/suchmaschinenoptimierung', 301));
    Route::get('/sea', fn () => redirect('/suchmaschinenwerbung', 301));

    // Betrieb, Hosting & Wartung
    Route::get('/betrieb-hosting-wartung', [PageController::class, 'maintenance'])->name('de.maintenance');

    // Hierarchical solution pages (must be last to catch all paths)
    Route::get('/loesungen/{path}', [PageController::class, 'solutionHierarchy'])
        ->where('path', '.*')
        ->name('de.solutions.hierarchy');

    // Local Landing Pages Hub & Detail
    Route::get('/in', [PageController::class, 'localHub'])->name('de.local.hub');
    Route::get('/in/{slug}', [PageController::class, 'localLanding'])->name('de.local');
});

/*
|--------------------------------------------------------------------------
| English Routes (/en/ Prefix)
|--------------------------------------------------------------------------
*/
Route::prefix('en')->middleware('locale:en')->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('en.home');
    Route::get('/solutions', [PageController::class, 'solutions'])->name('en.solutions');
    // Legacy route for flat solution details (backwards compatibility)
    Route::get('/solutions/{slug}', [PageController::class, 'solutionDetail'])->name('en.solutions.show');
    Route::get('/references', [PageController::class, 'references'])->name('en.references');
    Route::get('/references/{slug}', [PageController::class, 'referenceDetail'])->name('en.references.show');
    Route::get('/about-us', [PageController::class, 'about'])->name('en.about');
    Route::get('/contact', [PageController::class, 'contact'])->name('en.contact');
    Route::post('/contact', [PageController::class, 'contactSubmit'])->name('en.contact.submit');
    Route::get('/request-sent', [PageController::class, 'contactThankYou'])->name('en.contact.thank-you');
    Route::get('/imprint', [PageController::class, 'imprint'])->name('en.imprint');
    Route::get('/privacy', [PageController::class, 'privacy'])->name('en.privacy');
    Route::get('/terms', [PageController::class, 'agb'])->name('en.agb');
    Route::get('/accessibility', [PageController::class, 'accessibility'])->name('en.accessibility');
    // Blog -> Guides Redirect (301)
    Route::get('/blog', fn () => redirect('/en/guides', 301))->name('en.blog');
    Route::get('/blog/{slug}', fn ($slug) => redirect("/en/guides/{$slug}", 301))->name('en.blog.show');

    // Guides
    Route::get('/guides', [PageController::class, 'guideOverview'])->name('en.guides');
    Route::get('/guides/{slug}', [PageController::class, 'guide'])->name('en.guide.show');

    // SEO & SEA (new URLs)
    Route::get('/search-engine-optimization', [PageController::class, 'seo'])->name('en.seo');
    Route::get('/search-engine-advertising', [PageController::class, 'sea'])->name('en.sea');
    // Legacy redirects (301)
    Route::get('/seo', fn () => redirect('/en/search-engine-optimization', 301));
    Route::get('/sea', fn () => redirect('/en/search-engine-advertising', 301));

    // Hosting & Maintenance
    Route::get('/hosting-maintenance', [PageController::class, 'maintenance'])->name('en.maintenance');

    // Hierarchical solution pages (must be last to catch all paths)
    Route::get('/solutions/{path}', [PageController::class, 'solutionHierarchy'])
        ->where('path', '.*')
        ->name('en.solutions.hierarchy');
});

/*
|--------------------------------------------------------------------------
| Locale-Independent Routes
|--------------------------------------------------------------------------
*/

// Quote Client Routes (Token-based access)
Route::prefix('angebot')->group(function () {
    Route::get('/{token}', [QuoteController::class, 'show'])->name('quotes.show');
    Route::post('/{token}/accept', [QuoteController::class, 'accept'])->name('quotes.accept');
    Route::post('/{token}/update-options', [QuoteController::class, 'updateOptions'])->name('quotes.options');
    Route::get('/{token}/pdf', [QuoteController::class, 'pdf'])->name('quotes.pdf');
    Route::get('/{token}/angenommen', [QuoteController::class, 'accepted'])->name('quotes.accepted');
});

// Sitemap Route
Route::get('/sitemap.xml', function () {
    return response(
        SitemapGenerator::create(config('app.url'))->getSitemap()->render(),
        200,
        ['Content-Type' => 'application/xml']
    );
})->name('sitemap');

// robots.txt Route
Route::get('/robots.txt', function () {
    $content = "User-agent: *\n";
    $content .= "Allow: /\n";
    $content .= "Disallow: /admin\n";
    $content .= "Disallow: /dashboard\n";
    $content .= "\n";
    $content .= 'Sitemap: '.url('/sitemap.xml');

    return response($content, 200, ['Content-Type' => 'text/plain']);
})->name('robots');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin PDF Downloads
    Route::prefix('admin/download')->group(function () {
        Route::get('/invoice/{invoice}', function (\App\Models\Invoice $invoice) {
            $pdfService = app(\App\Services\Quote\PdfService::class);

            return $pdfService->downloadInvoice($invoice);
        })->name('admin.invoices.download');

        Route::get('/quote/{quote}', function (\App\Models\Quote $quote) {
            $pdfService = app(\App\Services\Quote\PdfService::class);

            return $pdfService->downloadQuote($quote);
        })->name('admin.quotes.download');

        Route::get('/contract/{contract}', function (\App\Models\Contract $contract) {
            $pdfService = app(\App\Services\Quote\PdfService::class);

            return $pdfService->downloadContract($contract);
        })->name('admin.contracts.download');
    });
});

require __DIR__.'/auth.php';
