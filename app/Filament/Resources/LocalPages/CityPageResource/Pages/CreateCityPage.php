<?php

namespace App\Filament\Resources\LocalPages\CityPageResource\Pages;

use App\Filament\Resources\LocalPages\CityPageResource;
use App\Models\Page;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateCityPage extends CreateRecord
{
    use Translatable;

    protected static string $resource = CityPageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = Page::TYPE_LOCAL;

        $city = $data['content']['city'] ?? '';
        $region = $data['content']['region'] ?? 'Rhein-Main';

        if ($city && empty($data['content']['intro']['headline'])) {
            $data['content'] = array_merge($data['content'] ?? [], self::getDefaultTemplate($city, $region));
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private static function getDefaultTemplate(string $city, string $region): array
    {
        return [
            'city' => $city,
            'region' => $region,
            'intro' => [
                'headline' => "Webagentur {$city}",
                'text' => "Ihre lokale Webagentur in {$city} – wir entwickeln maßgeschneiderte digitale Lösungen für Unternehmen in der Region {$region}.",
                'local_context' => "Als Webagentur mit Fokus auf {$city} und das {$region}-Gebiet verstehen wir die lokalen Anforderungen und Besonderheiten Ihres Marktes.",
            ],
            'solutions' => [
                'headline' => "Unsere Leistungen für Unternehmen in {$city}",
                'text' => 'Von der Unternehmenswebsite bis zur digitalen Plattform – wir bieten das komplette Spektrum moderner Webentwicklung.',
            ],
            'why' => [
                'headline' => "Warum sdWebdesign in {$city}?",
                'text' => 'Persönliche Beratung, lokale Erreichbarkeit und technische Expertise für Ihr Projekt.',
                'bullets' => [
                    'Persönliche Beratung vor Ort in '.$city,
                    'Schnelle Reaktionszeiten durch regionale Nähe',
                    'Verständnis für lokale Geschäftsanforderungen',
                ],
            ],
            'local_signal' => [
                'headline' => 'Lokale Expertise',
                'text' => "Wir kennen die Geschäftslandschaft in {$city} und im {$region}-Gebiet und wissen, worauf es bei digitalen Projekten in Ihrer Region ankommt.",
            ],
            'cta' => [
                'button_text' => 'Projekt besprechen',
            ],
        ];
    }
}
