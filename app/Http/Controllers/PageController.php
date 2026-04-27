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

        $organizationSchema = $this->buildOrganizationSchema();
        $websiteSchema = $this->buildWebsiteSchema();

        return view('pages.home', compact('page', 'organizationSchema', 'websiteSchema'));
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

        $personSchema = $this->buildFounderPersonSchema($page);

        return view('pages.about', compact('page', 'personSchema'));
    }

    public function contact(): View
    {
        $page = Page::findByType(Page::TYPE_CONTACT);

        if (! $page) {
            abort(404);
        }

        $this->setSeoMeta($page);

        // /kontakt is the strongest local-intent page on the site; emit
        // the organization entity here too so it's cross-verifiable with
        // the homepage and with the GBP profile (shared @id).
        $organizationSchema = $this->buildOrganizationSchema();

        return view('pages.contact', compact('page', 'organizationSchema'));
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

    public function accessibility(): View
    {
        $page = Page::findByType(Page::TYPE_ACCESSIBILITY);

        if (! $page) {
            abort(404);
        }

        $this->setSeoMeta($page);

        return view('pages.accessibility', compact('page'));
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

        $blogPostingSchema = $this->buildGuideBlogPostingSchema($page);

        return view('pages.guide', compact('page', 'relatedSolutions', 'otherGuides', 'blogPostingSchema'));
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

        $localBusinessSchema = $this->buildLocalBusinessSchema($page);

        return view('pages.local', compact('page', 'solutionHubs', 'seoPage', 'seaPage', 'localBusinessSchema'));
    }

    /**
     * Defensive guard for translatable text fields: Spatie's Translatable
     * deserialises JSON columns automatically, but if an editor pasted a
     * literal `{"de":"…","en":"…"}` string into Filament's plain-text
     * field (instead of using the per-locale translation field), the
     * column ends up double-encoded and we get the raw JSON in $title.
     * This unwraps that case and picks the current locale's value.
     */
    private function resolveTranslatedString(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_array($value)) {
            return $value[app()->getLocale()] ?? ($value['de'] ?? (array_values($value)[0] ?? null));
        }
        if (is_string($value) && str_starts_with(trim($value), '{') && str_contains($value, '"de"')) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded[app()->getLocale()] ?? ($decoded['de'] ?? (array_values($decoded)[0] ?? $value));
            }
        }

        return is_string($value) ? $value : (string) $value;
    }

    private function setSeoMeta(Page $page): void
    {
        $title = $this->resolveTranslatedString($page->meta_title) ?? $this->resolveTranslatedString($page->title) ?? '';
        $description = $this->resolveTranslatedString($page->meta_description) ?? '';

        // Strip a manual brand suffix — the SEO package appends it automatically.
        // Historically editors typed "| sdWebdesign" into meta_title in Filament,
        // producing a duplicated "Foo | sdWebdesign | sdWebdesign" in the rendered title.
        $title = preg_replace('/\s*\|\s*sd\s?webdesign\s*$/iu', '', $title);

        SEOMeta::setTitle($title);
        if ($description) {
            SEOMeta::setDescription($description);
        }
        OpenGraph::setTitle($title);
        if ($description) {
            OpenGraph::setDescription($description);
        }

        // Per-page noindex toggle (content.meta.noindex). Used to keep pages
        // accessible via direct link while removing them from Google's index —
        // typically for thin/duplicate local landing pages that are still
        // accessible but shouldn't compete with the featured ones.
        if ($page->getSection('meta.noindex') === true) {
            SEOMeta::setRobots('noindex, follow');
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

    /**
     * Canonical base URL used when emitting JSON-LD. Forces https on
     * production hostnames in case APP_URL is misconfigured; leaves local
     * dev hostnames (.test, localhost) untouched so fixtures still work.
     */
    private function schemaBaseUrl(): string
    {
        $url = rtrim(config('app.url'), '/');

        if (str_contains($url, '.test') || str_contains($url, 'localhost')) {
            return $url;
        }

        if (! str_starts_with($url, 'https://')) {
            $url = 'https://'.preg_replace('#^https?://#', '', $url);
        }

        return $url;
    }

    /**
     * Organization schema — the authoritative entity for this site.
     * Reused as a referenced @id from LocalBusiness, BlogPosting,
     * Article author/publisher, and any other schema that points at
     * the agency as the owning organization.
     *
     * @return array<string, mixed>
     */
    private function buildOrganizationSchema(): array
    {
        $settings = \App\Models\Setting::first();
        $baseUrl = $this->schemaBaseUrl();

        $address = array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $settings?->street,
            'postalCode' => $settings?->postal_code,
            'addressLocality' => $settings?->city,
            'addressCountry' => 'DE',
        ]);

        $sameAs = array_values(array_filter([
            $settings?->linkedin_url,
            $settings?->xing_url,
            $settings?->instagram_url,
            $settings?->facebook_url,
            $settings?->github_url,
            $settings?->twitter_url,
        ]));

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => $baseUrl.'/#organization',
            'name' => $settings?->company_name ?? 'sdWebdesign',
            'url' => $baseUrl,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $baseUrl.'/apple-touch-icon.png',
            ],
            'image' => $baseUrl.'/apple-touch-icon.png',
            'address' => $address,
            'email' => $settings?->email,
            'telephone' => $settings?->mobile ?? $settings?->phone,
            'sameAs' => $sameAs,
        ], fn ($v) => $v !== null && $v !== '' && $v !== []);
    }

    /**
     * Person schema for the about page — anchors Steffen as the named
     * principal of the agency. The sameAs ladder (LinkedIn, Xing, GitHub)
     * lets AI search engines and Google's Knowledge Graph cross-verify
     * the identity, which is part of the Phase D personal-brand strategy.
     *
     * Pulls the LinkedIn URL from the first team member's `linkedin`
     * field, falling back to the global Settings.linkedin_url so editors
     * can override per-person via Filament without losing the org-wide
     * profile link.
     *
     * @return array<string, mixed>
     */
    private function buildFounderPersonSchema(Page $page): array
    {
        $settings = \App\Models\Setting::first();
        $baseUrl = $this->schemaBaseUrl();

        $members = $page->getSection('team', [])['members'] ?? [];
        $founder = collect($members)->first(fn ($m) => ($m['name'] ?? null) === 'Steffen Fasselt')
            ?? ($members[0] ?? []);

        $sameAs = array_values(array_filter([
            $founder['linkedin'] ?? null,
            $settings?->linkedin_url,
            $settings?->xing_url,
            $settings?->github_url,
        ]));

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            '@id' => $baseUrl.'/ueber-uns#steffen-fasselt',
            'name' => $founder['name'] ?? 'Steffen Fasselt',
            'jobTitle' => $founder['role'] ?? 'Senior Product Owner & Plattform-Architekt',
            'description' => $founder['description'] ?? null,
            'url' => $baseUrl.'/ueber-uns',
            'worksFor' => ['@id' => $baseUrl.'/#organization'],
            'sameAs' => $sameAs,
        ], fn ($v) => $v !== null && $v !== '' && $v !== []);
    }

    /**
     * WebSite schema with SearchAction so Google is eligible to show
     * a Sitelinks Search Box for the site. Targets the /ratgeber
     * search (implemented by BlogController::index via ?search=).
     *
     * @return array<string, mixed>
     */
    private function buildWebsiteSchema(): array
    {
        $baseUrl = $this->schemaBaseUrl();
        $settings = \App\Models\Setting::first();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => $baseUrl.'/#website',
            'url' => $baseUrl,
            'name' => $settings?->company_name ?? 'sdWebdesign',
            'inLanguage' => 'de-DE',
            'publisher' => ['@id' => $baseUrl.'/#organization'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $baseUrl.'/ratgeber?search={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * Build the BlogPosting schema for a guide page so it becomes
     * eligible for Article rich results.
     *
     * @return array<string, mixed>
     */
    private function buildGuideBlogPostingSchema(Page $page): array
    {
        $settings = \App\Models\Setting::first();
        $baseUrl = $this->schemaBaseUrl();

        $url = $baseUrl.$page->getUrl();
        $orgId = $baseUrl.'/#organization';

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $url,
            ],
            'headline' => $page->meta_title ?? $page->title,
            'description' => $page->meta_description,
            'url' => $url,
            'datePublished' => $page->created_at?->toIso8601String(),
            'dateModified' => ($page->updated_at ?? $page->created_at)?->toIso8601String(),
            'inLanguage' => 'de-DE',
            'author' => [
                '@type' => 'Organization',
                '@id' => $orgId,
                'name' => $settings?->company_name ?? 'sdWebdesign',
                'url' => $baseUrl,
            ],
            'publisher' => [
                '@type' => 'Organization',
                '@id' => $orgId,
                'name' => $settings?->company_name ?? 'sdWebdesign',
                'url' => $baseUrl,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $baseUrl.'/apple-touch-icon.png',
                ],
            ],
            'image' => $baseUrl.'/apple-touch-icon.png',
        ], fn ($v) => $v !== null && $v !== '');
    }

    /**
     * Build the LocalBusiness / ProfessionalService schema for a local landing page.
     *
     * Emitted as a raw <script type="application/ld+json"> block in the view so the
     * full nested structure (geo, openingHours, hasOfferCatalog, sameAs) survives —
     * the JsonLd facade collapses some of these on output.
     *
     * @return array<string, mixed>
     */
    private function buildLocalBusinessSchema(Page $page): array
    {
        $settings = \App\Models\Setting::first();
        $city = $page->getSection('city', $page->title);
        $baseUrl = $this->schemaBaseUrl();

        $address = array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $settings?->street,
            'postalCode' => $settings?->postal_code,
            'addressLocality' => $settings?->city,
            'addressCountry' => 'DE',
        ]);

        $sameAs = array_values(array_filter([
            $settings?->linkedin_url,
            $settings?->xing_url,
            $settings?->instagram_url,
            $settings?->facebook_url,
            $settings?->github_url,
        ]));

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            '@id' => $baseUrl.'/#organization',
            'name' => $settings?->company_name ?? 'sdWebdesign',
            'url' => $baseUrl,
            'logo' => $baseUrl.'/apple-touch-icon.png',
            'image' => $baseUrl.'/apple-touch-icon.png',
            'description' => $page->meta_description ?? "Webagentur für {$city}: Websites, Online-Shops und digitale Systeme",
            'address' => $address,
            'geo' => [
                '@type' => 'GeoCoordinates',
                // Hannah-Arendt-Str. 29, 60438 Frankfurt am Main (Kalbach-Riedberg).
                // Schema geo must match GBP pin within ~100m or Google ignores the entity.
                'latitude' => 50.18430,
                'longitude' => 8.65870,
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => $city,
            ],
            'telephone' => $settings?->mobile ?? $settings?->phone,
            'email' => $settings?->email,
            'priceRange' => '€€€',
            'openingHoursSpecification' => [[
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'opens' => '08:00',
                'closes' => '18:00',
            ]],
            'sameAs' => $sameAs,
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

        return array_filter($schema, fn ($v) => $v !== null && $v !== '' && $v !== []);
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
