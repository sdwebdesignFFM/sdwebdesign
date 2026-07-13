<?php

namespace Database\Seeders;

use App\Models\Redirect;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RedirectSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed 301 redirects for dead legacy URLs left over from the
     * WordPress -> Laravel migration.
     *
     * Idempotent: keyed on `from_url` via updateOrCreate, so it is safe
     * to run repeatedly (e.g. on every deploy).
     */
    public function run(): void
    {
        foreach ($this->redirects() as $redirect) {
            Redirect::updateOrCreate(
                ['from_url' => $redirect['from_url']],
                [
                    'to_url' => $redirect['to_url'],
                    'status_code' => 301,
                    'is_active' => true,
                    'notes' => $redirect['notes'] ?? null,
                ]
            );
        }
    }

    /**
     * @return array<int, array{from_url: string, to_url: string, notes?: string}>
     */
    protected function redirects(): array
    {
        return [
            // Old WordPress glossary terms mapped to the most relevant guide.
            [
                'from_url' => '/glossar/wordpress-rest-endpoints',
                'to_url' => '/ratgeber/api-first-architektur',
                'notes' => 'Legacy glossary term (REST/API) -> API-First guide',
            ],
            [
                'from_url' => '/glossar/wordpress-transients',
                'to_url' => '/ratgeber/wordpress-oder-individuell',
                'notes' => 'Legacy glossary term (WordPress) -> WordPress vs. custom guide',
            ],
            [
                'from_url' => '/glossar/post-meta',
                'to_url' => '/ratgeber/wordpress-oder-individuell',
                'notes' => 'Legacy glossary term (WordPress) -> WordPress vs. custom guide',
            ],

            // Glossary terms without a matching guide -> guides overview.
            [
                'from_url' => '/glossar/light-mode-design',
                'to_url' => '/ratgeber',
                'notes' => 'Legacy glossary term (no matching guide)',
            ],
            [
                'from_url' => '/glossar/modal-window',
                'to_url' => '/ratgeber',
                'notes' => 'Legacy glossary term (no matching guide)',
            ],
            [
                'from_url' => '/glossar/active-state',
                'to_url' => '/ratgeber',
                'notes' => 'Legacy glossary term (no matching guide)',
            ],
            [
                'from_url' => '/glossar/typography-scale',
                'to_url' => '/ratgeber',
                'notes' => 'Legacy glossary term (no matching guide)',
            ],

            // Catch-all for every other (unknown) glossary URL.
            [
                'from_url' => '/glossar/*',
                'to_url' => '/ratgeber',
                'notes' => 'Catch-all for all remaining legacy glossary URLs',
            ],

            // Legacy solution URL -> reinstated accessibility offer page
            // (seeded by AccessibleWebDesignPageSeeder).
            [
                'from_url' => '/loesungen/barrierefreies-webdesign',
                'to_url' => '/loesungen/websites/barrierefreies-webdesign',
                'notes' => 'Legacy solution URL -> BFSG/accessibility offer page',
            ],
        ];
    }
}
