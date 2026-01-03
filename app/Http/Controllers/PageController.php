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

        // Get all solution hub pages including SEO & SEA for the listing (sorted by sort_order)
        $solutionPages = Page::active()
            ->whereIn('type', [Page::TYPE_SOLUTION_HUB, Page::TYPE_SEO, Page::TYPE_SEA])
            ->orderBy('sort_order')
            ->get();

        $this->setSeoMeta($page);

        return view('pages.solutions', compact('page', 'solutionPages'));
    }

    public function solutionDetail(string $slug): View
    {
        $page = Page::findBySlug($slug);

        if (! $page) {
            abort(404);
        }

        // Delegate hub pages to hierarchical handler
        if ($page->type === Page::TYPE_SOLUTION_HUB) {
            return $this->solutionHierarchy($slug);
        }

        if ($page->type !== Page::TYPE_SOLUTION_DETAIL) {
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
            ->orderBy('sort_order')
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

        return redirect(localized_route('contact'))->with('success', $successMessage);
    }

    public function contactThankYou(): View|RedirectResponse
    {
        // Redirect if accessed directly without session data
        if (! session()->has('contact_submitted')) {
            return redirect(localized_route('home'));
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

    public function agb(): View
    {
        $settings = \App\Models\Setting::instance();
        $locale = app()->getLocale();

        $title = $locale === 'de' ? 'Allgemeine Geschäftsbedingungen' : 'Terms and Conditions';
        SEOMeta::setTitle($title);
        SEOMeta::setDescription($locale === 'de'
            ? 'Allgemeine Geschäftsbedingungen (AGB) von '.$settings->company_name
            : 'Terms and Conditions of '.$settings->company_name);

        return view('pages.agb', compact('settings'));
    }

    public function solutionHierarchy(string $path): View
    {
        $page = Page::findByHierarchicalSlug($path);

        if (! $page || ! in_array($page->type, [Page::TYPE_SOLUTION_HUB, Page::TYPE_SOLUTION_DETAIL])) {
            abort(404);
        }

        $this->setSeoMeta($page);
        $this->setBreadcrumbsJsonLd($page);

        // Hub page: show children
        if ($page->type === Page::TYPE_SOLUTION_HUB) {
            $childPages = $page->children;

            // Get related guide for this hub (if any)
            $relatedGuide = null;
            $guideSlug = $page->getSection('related_guide_slug');
            if ($guideSlug) {
                $relatedGuide = Page::findBySlug($guideSlug);
            }

            return view('pages.solution-hub', compact('page', 'childPages', 'relatedGuide'));
        }

        // Detail page: show related solutions from same parent
        $otherSolutions = Page::active()
            ->where('parent_id', $page->parent_id)
            ->where('id', '!=', $page->id)
            ->orderBy('sort_order')
            ->get();

        // Get related guide for this detail page
        $relatedGuide = null;
        $guideSlug = $page->getSection('related_guide_slug');
        if ($guideSlug) {
            $relatedGuide = Page::findBySlug($guideSlug);
        }

        return view('pages.solution-detail', compact('page', 'otherSolutions', 'relatedGuide'));
    }

    public function guideOverview(): View
    {
        $page = Page::findByType(Page::TYPE_GUIDE_OVERVIEW);

        if (! $page) {
            abort(404);
        }

        $this->setSeoMeta($page);

        // Get published guides with pagination (12 per page)
        $guides = Page::active()
            ->ofType(Page::TYPE_GUIDE)
            ->orderBy('sort_order')
            ->paginate(12);

        return view('pages.guide-overview', compact('page', 'guides'));
    }

    public function guide(string $slug): View
    {
        $page = Page::findBySlug($slug);

        if (! $page || $page->type !== Page::TYPE_GUIDE) {
            abort(404);
        }

        $this->setSeoMeta($page);

        // Get related solution pages mentioned in this guide
        $relatedSolutions = collect();
        $relatedSlugs = $page->getSection('related_solutions', []);
        foreach ($relatedSlugs as $relatedSlug) {
            $relatedPage = Page::findBySlug($relatedSlug);
            if ($relatedPage) {
                $relatedSolutions->push($relatedPage);
            }
        }

        // Get other guides
        $otherGuides = Page::active()
            ->ofType(Page::TYPE_GUIDE)
            ->where('id', '!=', $page->id)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('pages.guide', compact('page', 'relatedSolutions', 'otherGuides'));
    }

    public function seo(): View
    {
        $page = Page::findByType(Page::TYPE_SEO);

        if (! $page) {
            abort(404);
        }

        $this->setSeoMeta($page);

        // Get related solution pages for linking
        $relatedSolutions = Page::active()
            ->whereIn('type', [Page::TYPE_SOLUTION_HUB])
            ->orderBy('sort_order')
            ->get();

        return view('pages.seo', compact('page', 'relatedSolutions'));
    }

    public function sea(): View
    {
        $page = Page::findByType(Page::TYPE_SEA);

        if (! $page) {
            abort(404);
        }

        $this->setSeoMeta($page);

        // Get related solution pages for linking
        $relatedSolutions = Page::active()
            ->whereIn('type', [Page::TYPE_SOLUTION_HUB])
            ->orderBy('sort_order')
            ->get();

        // Get SEO page for cross-linking
        $seoPage = Page::findByType(Page::TYPE_SEO);

        return view('pages.sea', compact('page', 'relatedSolutions', 'seoPage'));
    }

    public function maintenance(): View
    {
        $page = Page::findByType(Page::TYPE_MAINTENANCE);

        if (! $page) {
            abort(404);
        }

        $this->setSeoMeta($page);

        return view('pages.maintenance', compact('page'));
    }

    public function localHub(): View
    {
        $page = Page::findByType(Page::TYPE_LOCAL_HUB);

        if (! $page) {
            abort(404);
        }

        $this->setSeoMeta($page);

        // Get all local landing pages grouped by region
        $localPages = Page::active()
            ->where('type', Page::TYPE_LOCAL)
            ->orderBy('title')
            ->get();

        return view('pages.local-hub', compact('page', 'localPages'));
    }

    public function localLanding(string $slug): View
    {
        $page = Page::findBySlug($slug);

        if (! $page || $page->type !== Page::TYPE_LOCAL) {
            abort(404);
        }

        $this->setSeoMeta($page);
        $this->setLocalBusinessJsonLd($page);
        $this->setLocalBreadcrumbsJsonLd($page);

        // Get all solution hubs for linking
        $solutionHubs = Page::active()
            ->where('type', Page::TYPE_SOLUTION_HUB)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        // Get SEO and SEA pages
        $seoPage = Page::findByType(Page::TYPE_SEO);
        $seaPage = Page::findByType(Page::TYPE_SEA);

        return view('pages.local', compact('page', 'solutionHubs', 'seoPage', 'seaPage'));
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

    private function setBreadcrumbsJsonLd(Page $page): void
    {
        $breadcrumbs = $page->getBreadcrumbs();
        $items = [];
        $position = 1;

        // Add home as first item
        $locale = app()->getLocale();
        $homeUrl = $locale === 'en' ? url('/en') : url('/');
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => 'Home',
            'item' => $homeUrl,
        ];

        // Add solutions overview
        $solutionsUrl = $locale === 'en' ? url('/en/solutions') : url('/loesungen');
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $locale === 'en' ? 'Solutions' : 'Lösungen',
            'item' => $solutionsUrl,
        ];

        // Add breadcrumb items
        foreach ($breadcrumbs as $url => $name) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $name,
                'item' => url($url),
            ];
        }

        JsonLd::setType('BreadcrumbList');
        JsonLd::addValue('itemListElement', $items);
    }

    private function setLocalBusinessJsonLd(Page $page): void
    {
        $settings = \App\Models\Setting::first();
        $city = $page->getSection('city', $page->title);

        // Build comprehensive LocalBusiness schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            '@id' => url($page->getUrl()).'#localbusiness',
            'name' => $settings?->company_name ?? 'sdWebdesign',
            'description' => $page->meta_description ?? "Webagentur für {$city}: Websites, Online-Shops und digitale Systeme",
            'url' => url($page->getUrl()),
            'telephone' => $settings?->mobile ?? $settings?->phone,
            'email' => $settings?->email,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $settings?->street,
                'postalCode' => $settings?->postal_code,
                'addressLocality' => $settings?->city,
                'addressCountry' => 'DE',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => '50.1109',
                'longitude' => '8.6821',
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => $city,
            ],
            'priceRange' => '€€€',
            'openingHoursSpecification' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'opens' => '08:00',
                'closes' => '18:00',
            ],
            'sameAs' => array_filter([
                $settings?->linkedin_url,
                $settings?->xing_url,
                $settings?->github_url,
            ]),
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Webentwicklung & Digitale Lösungen',
                'itemListElement' => [
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Unternehmenswebsites']],
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'E-Commerce & Online-Shops']],
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Digitale Plattformen']],
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Mobile Anwendungen']],
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'SEO & SEA']],
                ],
            ],
        ];

        // Use raw JSON-LD instead of the facade for more control
        JsonLd::setType('ProfessionalService');
        JsonLd::setTitle($settings?->company_name ?? 'sdWebdesign');
        JsonLd::setDescription($page->meta_description ?? "Webagentur für {$city}");
        JsonLd::addValue('address', $schema['address']);
        JsonLd::addValue('areaServed', $schema['areaServed']);
        JsonLd::addValue('telephone', $schema['telephone']);
        JsonLd::addValue('email', $schema['email']);
        JsonLd::addValue('priceRange', '€€€');
    }

    private function setLocalBreadcrumbsJsonLd(Page $page): void
    {
        $locale = app()->getLocale();
        $city = $page->getSection('city', $page->title);

        $items = [];
        $position = 1;

        // Home
        $homeUrl = $locale === 'en' ? url('/en') : url('/');
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => 'Home',
            'item' => $homeUrl,
        ];

        // Local Hub
        $hubUrl = $locale === 'en' ? url('/en/in') : url('/in');
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $locale === 'en' ? 'Locations' : 'Standorte',
            'item' => $hubUrl,
        ];

        // Current city
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $city,
            'item' => url($page->getUrl()),
        ];

        // Note: Since we already set LocalBusiness, we add breadcrumbs via a script tag in the view
        // This is handled in the blade template
    }
}
