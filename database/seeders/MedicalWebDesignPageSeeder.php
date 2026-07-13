<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Industry offer page "Webdesign für Ärzte & Zahnärzte" as a child of the
 * "websites" hub. Targets the healthcare cluster (SE Ranking, July 2026):
 * "webdesign für zahnärzte" (590/month, KD 15), "homepage für ärzte"
 * (260/month, KD 29), "praxis homepage" (70/month, KD 22). Competitor
 * webdesign-doerrer.de runs the same industry-page playbook and earns
 * dedicated backlinks to its /webdesign-fuer-aerzte page.
 *
 * Run via:
 *   php artisan db:seed --class=MedicalWebDesignPageSeeder --force
 *
 * Idempotent: keyed on the German slug, re-running updates in place
 * (and overwrites manual Filament edits).
 */
class MedicalWebDesignPageSeeder extends Seeder
{
    public function run(): void
    {
        $hub = Page::query()
            ->where('type', Page::TYPE_SOLUTION_HUB)
            ->where('slug->de', 'websites')
            ->first();

        if (! $hub) {
            $this->command?->warn('Websites hub page not found - skipping MedicalWebDesignPageSeeder.');

            return;
        }

        $page = Page::query()
            ->where('type', Page::TYPE_SOLUTION_DETAIL)
            ->where('slug->de', 'webdesign-fuer-aerzte')
            ->first() ?? new Page;

        $page->fill([
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'parent_id' => $hub->id,
            'is_active' => true,
            'sort_order' => 5,
            'slug' => [
                'de' => 'webdesign-fuer-aerzte',
                'en' => 'web-design-for-medical-practices',
            ],
            'title' => [
                'de' => 'Webdesign für Ärzte & Zahnärzte',
                'en' => 'Web Design for Medical & Dental Practices',
            ],
            'meta_title' => [
                'de' => 'Webdesign für Ärzte & Zahnärzte – Praxis-Homepage vom Profi',
                'en' => 'Web Design for Medical & Dental Practices',
            ],
            'meta_description' => [
                'de' => 'Webdesign für Ärzte, Zahnärzte und Praxen: Praxis-Homepage mit Online-Terminbuchung, DSGVO-konformem Umgang mit Patientendaten, Barrierefreiheit nach BFSG und lokaler Sichtbarkeit bei Google.',
                'en' => 'Web design for medical and dental practices: practice websites with online appointment booking, GDPR-compliant patient data handling, accessibility and local Google visibility.',
            ],
            'content' => [
                'de' => $this->germanContent(),
                'en' => $this->englishContent(),
            ],
        ]);

        $page->save();
    }

    /**
     * @return array<string, mixed>
     */
    private function germanContent(): array
    {
        return [
            'hero' => [
                'icon' => 'heart',
                'tagline' => 'Die Praxis-Homepage, die neue Patienten überzeugt',
                'description' => 'Patienten suchen heute zuerst bei Google – und entscheiden in Sekunden, ob eine Praxis vertrauenswürdig wirkt. Wir entwickeln Websites für Ärzte, Zahnärzte und Therapeuten, die gefunden werden, Vertrauen aufbauen und Termine bringen: DSGVO-konform, barrierefrei und ohne Baukasten-Einerlei.',
            ],
            'ideal_for' => 'Arztpraxen, Zahnarztpraxen, MVZ und Therapeuten, die online neue Patienten gewinnen und dem Praxisteam Telefonate ersparen wollen.',
            'when' => [
                'title' => 'Wann sich eine professionelle Praxis-Homepage lohnt',
                'intro' => 'Eine Praxis-Website ist kein Pflichtprogramm, sondern Ihr wirksamstes Instrument zur Patientengewinnung – wenn sie diese Punkte erfüllt:',
                'conditions' => [
                    'Neue Patienten finden Ihre Praxis bei Google – nicht nur die Konkurrenz',
                    'Termine werden online gebucht statt am Telefon erfragt (entlastet die Anmeldung spürbar)',
                    'Leistungen, Team und Praxisräume bauen schon vor dem ersten Besuch Vertrauen auf',
                    'Anfahrt, Sprechzeiten und Formulare sind ohne Suchen auffindbar – auch mobil im Wartezimmer-Moment',
                ],
                'note' => 'Wichtig: Praxis-Websites richten sich an Verbraucher. Damit gelten DSGVO-Anforderungen an Gesundheitsdaten besonders streng – und Barrierefreiheit nach BFSG ist für viele Praxen Pflicht, für alle sinnvoll: gerade ältere Patienten profitieren davon.',
            ],
            'features' => [
                'title' => 'Was eine gute Praxis-Website ausmacht',
                'intro' => 'Aus Projekten mit Praxen wissen wir, worauf es ankommt:',
                'items' => [
                    'Online-Terminbuchung: eigene Lösung oder saubere Anbindung von Doctolib, jameda & Co.',
                    'DSGVO-konformer Umgang mit Gesundheitsdaten (Formulare, Einwilligungen, Auftragsverarbeitung)',
                    'Barrierefreiheit nach WCAG 2.1 / BFSG – bedienbar für alle Patienten',
                    'Lokale Sichtbarkeit: Google Business Profile, LocalBusiness-Schema, Stadtteil-Keywords',
                    'Leistungsseiten je Behandlung – so ranken Sie für „Zahnreinigung Frankfurt" statt nur für Ihren Namen',
                    'Team- und Praxisfotos statt Stockbilder – das entscheidet über Vertrauen',
                    'Anfahrt, Sprechzeiten, Rezept-Anfragen und Downloads prominent statt versteckt',
                    'Ladezeiten unter 2 Sekunden – Patienten mit Schmerzen warten nicht',
                ],
                'note' => 'Bei Inhalten achten wir auf eine sachliche Darstellung Ihrer Leistungen – die berufsrechtlichen Grenzen ärztlicher Werbung (HWG, Berufsordnung) haben wir dabei im Blick; die inhaltliche Freigabe liegt bei Ihnen.',
            ],
            'process' => [
                'title' => 'So entsteht Ihre Praxis-Website',
                'steps' => [
                    [
                        'title' => 'Kennenlernen & Praxis verstehen',
                        'description' => 'Welche Patienten wollen Sie gewinnen? Welche Leistungen tragen die Praxis? Was nervt heute im Praxisalltag (Telefon, Terminausfälle, Formulare)? Daraus entsteht die Struktur.',
                    ],
                    [
                        'title' => 'Design mit Praxis-Persönlichkeit',
                        'description' => 'Individuelles Design auf Basis Ihrer Praxisräume, Farben und Fotos – kein Template, das drei andere Praxen im Umkreis auch nutzen.',
                    ],
                    [
                        'title' => 'Technische Umsetzung',
                        'description' => 'Sauberer Code, CMS für eigenständige Pflege, Terminbuchung, DSGVO-konforme Formulare, Barrierefreiheit und lokales SEO-Fundament.',
                    ],
                    [
                        'title' => 'Launch & Sichtbarkeit',
                        'description' => 'Google Business Profile einrichten oder optimieren, Einträge in Arztverzeichnissen konsistent machen, Erfolgsmessung datenschutzkonform aufsetzen.',
                    ],
                ],
            ],
            'benefits' => [
                'Neue Patienten über Google statt nur über Empfehlungen',
                'Weniger Telefonaufkommen durch Online-Terminbuchung und gute Selbstinformation',
                'Rechtssicherheit bei DSGVO und Barrierefreiheit (BFSG)',
                'Eine Website, die Sie über das CMS selbst aktuell halten können',
            ],
            'scenarios' => [
                'Neue Praxis-Homepage für Zahnarzt- oder Arztpraxis erstellen',
                'Veraltete Praxis-Website modernisieren und DSGVO-/BFSG-konform machen',
                'Online-Terminbuchung in bestehende Website integrieren',
                'Lokale Google-Sichtbarkeit der Praxis verbessern (Praxis-SEO)',
            ],
            'limitations' => [
                'title' => 'Wo wir ehrlich sind',
                'note' => 'Eine gute Website ist die Basis – aber kein Selbstläufer.',
                'items' => [
                    'Bewertungen auf Google und jameda müssen aus der Praxis heraus aktiv aufgebaut werden – wir liefern den Prozess, das Team muss ihn leben',
                    'Rechtsverbindliche Prüfung von Werbeaussagen (HWG, Berufsordnung) ersetzt unsere Arbeit nicht – im Zweifel gehört Ihre Kammer oder ein Fachanwalt dazu',
                    'In umkämpften Lagen (z. B. Frankfurter Innenstadt) braucht Top-Sichtbarkeit Zeit und kontinuierliche Pflege, keine Einmalzahlung',
                ],
            ],
            'differentiation' => [
                'title' => 'Warum keine Praxis-Baukasten-Lösung?',
                'text' => "Spezialisierte Praxis-Homepage-Anbieter liefern Templates im Abo: schnell live, aber austauschbar, mit begrenzter Terminbuchungs-Integration und ohne echtes lokales SEO. Nach drei Jahren Abo haben Sie mehr bezahlt als für eine eigene Website – und besitzen nichts.\nWir bauen Ihre Praxis-Website als Eigentum: individuelles Design, sauberer Code, erweiterbar um Recall-Funktionen, Patientenportale oder weitere Standorte.",
                'link_slug' => 'starter-website',
                'link_text' => 'Zur Starter-Website: der schlanke Einstieg',
            ],
            'next_steps' => [
                'title' => 'Passende nächste Schritte',
                'text' => 'Praxis-Websites verbinden wir auf Wunsch mit Barrierefreiheit nach BFSG und laufender Betreuung im Praxisalltag.',
                'links' => [
                    ['slug' => 'barrierefreies-webdesign', 'label' => 'Barrierefreies Webdesign nach BFSG & WCAG'],
                    ['slug' => 'betrieb-hosting-wartung', 'label' => 'Betrieb & Wartung: die Praxis-Website in guten Händen'],
                ],
            ],
            'cta' => [
                'text' => 'Lassen Sie uns über Ihre Praxis sprechen: Ich zeige Ihnen an konkreten Beispielen, wie Ihre Website Patienten gewinnt – und sage ehrlich, was sich für Ihre Praxisgröße lohnt und was nicht.',
                'button_text' => 'Kostenloses Praxis-Gespräch anfragen',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function englishContent(): array
    {
        return [
            'hero' => [
                'icon' => 'heart',
                'tagline' => 'The practice website that wins new patients',
                'description' => 'Patients search Google first – and decide within seconds whether a practice looks trustworthy. We build websites for doctors, dentists and therapists that get found, build trust and generate appointments: GDPR-compliant, accessible and free of template sameness.',
            ],
            'ideal_for' => 'Medical and dental practices, health centres and therapists who want to win new patients online and take pressure off the front desk.',
            'when' => [
                'title' => 'When a professional practice website pays off',
                'intro' => 'A practice website is not a box to tick – it is your most effective patient acquisition tool when it delivers on these points:',
                'conditions' => [
                    'New patients find your practice on Google – not just your competitors',
                    'Appointments are booked online instead of over the phone',
                    'Services, team and premises build trust before the first visit',
                    'Directions, opening hours and forms are easy to find – including on mobile',
                ],
                'note' => 'Practice websites address consumers, so GDPR requirements for health data apply strictly – and accessibility is mandatory for many practices and sensible for all: older patients in particular benefit.',
            ],
            'features' => [
                'title' => 'What makes a good practice website',
                'intro' => 'From projects with practices we know what matters:',
                'items' => [
                    'Online appointment booking: custom or clean integration of established booking providers',
                    'GDPR-compliant handling of health data (forms, consent, processing agreements)',
                    'Accessibility per WCAG 2.1 – usable for all patients',
                    'Local visibility: Google Business Profile, LocalBusiness schema, neighbourhood keywords',
                    'Service pages per treatment – so you rank for treatments, not just your name',
                    'Real team and practice photos instead of stock images',
                    'Directions, hours, prescription requests and downloads prominent instead of hidden',
                    'Load times under 2 seconds – patients in pain do not wait',
                ],
            ],
            'process' => [
                'title' => 'How your practice website is built',
                'steps' => [
                    [
                        'title' => 'Understanding your practice',
                        'description' => 'Which patients do you want to win? Which services carry the practice? What causes friction today (phone, no-shows, forms)? This shapes the structure.',
                    ],
                    [
                        'title' => 'Design with personality',
                        'description' => 'Individual design based on your premises, colours and photos – no template that three nearby practices also use.',
                    ],
                    [
                        'title' => 'Technical implementation',
                        'description' => 'Clean code, a CMS for independent editing, appointment booking, GDPR-compliant forms, accessibility and a local SEO foundation.',
                    ],
                    [
                        'title' => 'Launch & visibility',
                        'description' => 'Set up or optimise the Google Business Profile, make directory listings consistent, and measure results in a privacy-compliant way.',
                    ],
                ],
            ],
            'benefits' => [
                'New patients via Google instead of referrals only',
                'Less phone traffic thanks to online booking and good self-service information',
                'Legal certainty on GDPR and accessibility',
                'A website you can keep up to date yourself via the CMS',
            ],
            'scenarios' => [
                'Build a new website for a medical or dental practice',
                'Modernise an outdated practice website and make it compliant',
                'Integrate online appointment booking into an existing website',
                'Improve the local Google visibility of a practice',
            ],
            'limitations' => [
                'title' => 'Where we are honest',
                'note' => 'A good website is the foundation – not a self-runner.',
                'items' => [
                    'Reviews on Google and health portals must be built up actively by the practice – we provide the process, your team has to live it',
                    'Legally binding review of advertising claims under medical advertising law is a matter for your chamber or a specialist lawyer',
                    'In competitive locations, top visibility takes time and continuous care, not a one-off payment',
                ],
            ],
            'differentiation' => [
                'title' => 'Why not a practice website builder?',
                'text' => "Specialised practice-website providers deliver subscription templates: quick to launch but interchangeable, with limited booking integration and no real local SEO. After three years of subscription you have paid more than a custom site costs – and own nothing.\nWe build your practice website as property: individual design, clean code, extensible with recall features, patient portals or additional locations.",
                'link_slug' => 'starter-website',
                'link_text' => 'The lean way to start: Starter Website',
            ],
            'next_steps' => [
                'title' => 'Suitable next steps',
                'text' => 'On request we combine practice websites with accessibility compliance and ongoing care.',
                'links' => [
                    ['slug' => 'barrierefreies-webdesign', 'label' => 'Accessible web design (WCAG 2.1)'],
                    ['slug' => 'betrieb-hosting-wartung', 'label' => 'Hosting & maintenance: your website in good hands'],
                ],
            ],
            'cta' => [
                'text' => 'Let us talk about your practice: I will show you with concrete examples how your website wins patients – and tell you honestly what pays off for your practice size and what does not.',
                'button_text' => 'Request a free practice consultation',
            ],
        ];
    }
}
