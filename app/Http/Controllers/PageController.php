<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home(): View
    {
        $page = Page::findByType(Page::TYPE_HOME);

        if (! $page) {
            abort(404);
        }

        $this->setSeoMeta($page);
        JsonLd::setType('WebSite');
        JsonLd::setTitle($page->meta_title ?? $page->title);

        return view('pages.home', compact('page'));
    }

    public function solutions(): View
    {
        $page = Page::findByType(Page::TYPE_SOLUTIONS);

        if (! $page) {
            abort(404);
        }

        // Get all solution detail pages for the listing
        $solutionPages = Page::active()
            ->ofType(Page::TYPE_SOLUTION_DETAIL)
            ->get();

        $this->setSeoMeta($page);

        return view('pages.solutions', compact('page', 'solutionPages'));
    }

    public function solutionDetail(string $slug): View
    {
        $page = Page::findBySlug($slug);

        if (! $page || $page->type !== Page::TYPE_SOLUTION_DETAIL) {
            abort(404);
        }

        $this->setSeoMeta($page);

        // Get other solution pages for "Related Solutions" section
        $otherSolutions = Page::active()
            ->where('type', Page::TYPE_SOLUTION_DETAIL)
            ->where('id', '!=', $page->id)
            ->orderBy('title')
            ->get();

        return view('pages.solution-detail', compact('page', 'otherSolutions'));
    }

    public function references(): View
    {
        $page = Page::findByType(Page::TYPE_REFERENCES);

        if (! $page) {
            abort(404);
        }

        // Get all reference detail pages for the listing
        $referencePages = Page::active()
            ->ofType(Page::TYPE_REFERENCE_DETAIL)
            ->get();

        $this->setSeoMeta($page);

        return view('pages.references', compact('page', 'referencePages'));
    }

    public function referenceDetail(string $slug): View
    {
        $page = Page::findBySlug($slug);

        if (! $page || $page->type !== Page::TYPE_REFERENCE_DETAIL) {
            abort(404);
        }

        $this->setSeoMeta($page);

        // Get other reference pages for "Related Projects" section
        $otherReferences = Page::active()
            ->where('type', Page::TYPE_REFERENCE_DETAIL)
            ->where('id', '!=', $page->id)
            ->orderBy('title')
            ->limit(3)
            ->get();

        return view('pages.reference-detail', compact('page', 'otherReferences'));
    }

    public function about(): View
    {
        $page = Page::findByType(Page::TYPE_ABOUT);

        if (! $page) {
            abort(404);
        }

        $this->setSeoMeta($page);

        return view('pages.about', compact('page'));
    }

    public function contact(): View
    {
        $page = Page::findByType(Page::TYPE_CONTACT);

        if (! $page) {
            abort(404);
        }

        $this->setSeoMeta($page);

        return view('pages.contact', compact('page'));
    }

    public function contactSubmit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'project_type' => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
        ]);

        // TODO: Send email notification or store in database

        $page = Page::findByType(Page::TYPE_CONTACT);
        $successMessage = $page?->getSection('form.success_message')
            ?? 'Vielen Dank für Ihre Anfrage. Wir melden uns in der Regelinnerhalb von 24 Stunden bei Ihnen.';

        return redirect()->route('contact')->with('success', $successMessage);
    }

    public function contactThankYou(): View|RedirectResponse
    {
        // Redirect if accessed directly without session data
        if (! session()->has('contact_submitted')) {
            return redirect()->route('home');
        }

        $contactData = session('contact_data', []);

        SEOMeta::setTitle('Vielen Dank für Ihre Anfrage');
        SEOMeta::setRobots('noindex, nofollow');

        return view('pages.contact-thank-you', compact('contactData'));
    }

    public function imprint(): View
    {
        $page = Page::findByType(Page::TYPE_IMPRINT);

        if (! $page) {
            abort(404);
        }

        $this->setSeoMeta($page);

        return view('pages.imprint', compact('page'));
    }

    public function privacy(): View
    {
        $page = Page::findByType(Page::TYPE_PRIVACY);

        if (! $page) {
            abort(404);
        }

        $this->setSeoMeta($page);

        return view('pages.privacy', compact('page'));
    }

    private function setSeoMeta(Page $page): void
    {
        $title = $page->meta_title ?? $page->title;
        $description = $page->meta_description ?? '';

        SEOMeta::setTitle($title);
        if ($description) {
            SEOMeta::setDescription($description);
        }
        OpenGraph::setTitle($title);
        if ($description) {
            OpenGraph::setDescription($description);
        }
    }
}
