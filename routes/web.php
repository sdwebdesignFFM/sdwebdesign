<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\SitemapGenerator;

// Frontend Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/loesungen', [PageController::class, 'solutions'])->name('solutions');
Route::get('/loesungen/{slug}', [PageController::class, 'solutionDetail'])->name('solutions.show');
Route::get('/referenzen', [PageController::class, 'references'])->name('references');
Route::get('/referenzen/{slug}', [PageController::class, 'referenceDetail'])->name('references.show');
Route::get('/ueber-uns', [PageController::class, 'about'])->name('about');
Route::get('/kontakt', [PageController::class, 'contact'])->name('contact');
Route::post('/kontakt', [PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/anfrage-gesendet', [PageController::class, 'contactThankYou'])->name('contact.thank-you');
Route::get('/impressum', [PageController::class, 'imprint'])->name('imprint');
Route::get('/datenschutz', [PageController::class, 'privacy'])->name('privacy');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

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
    $content .= "Sitemap: " . url('/sitemap.xml');

    return response($content, 200, ['Content-Type' => 'text/plain']);
})->name('robots');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
