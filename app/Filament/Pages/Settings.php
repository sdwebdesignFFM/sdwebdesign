<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.settings';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return 'Einstellungen';
    }

    public function getTitle(): string
    {
        return 'Firmeneinstellungen';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    public static function getNavigationSort(): ?int
    {
        return 100;
    }

    public function mount(): void
    {
        $setting = Setting::instance();
        $this->form->fill($setting->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Firmeninformationen')
                    ->description('Grundlegende Informationen über Ihr Unternehmen')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('company_name')
                            ->label('Firmenname')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('owner_name')
                            ->label('Inhaber / Geschäftsführer')
                            ->maxLength(255),

                        TextInput::make('tagline')
                            ->label('Slogan / Tagline')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Section::make('Kontaktdaten')
                    ->description('So können Ihre Kunden Sie erreichen')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('email')
                            ->label('E-Mail')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Telefon')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('mobile')
                            ->label('Mobil')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('business_hours')
                            ->label('Geschäftszeiten')
                            ->maxLength(255)
                            ->placeholder('z.B. Mo - Fr 8:00 bis 17:00'),
                    ]),

                Section::make('Adresse')
                    ->description('Ihre Geschäftsadresse')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('street')
                            ->label('Straße und Hausnummer')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('postal_code')
                            ->label('Postleitzahl')
                            ->maxLength(10),

                        TextInput::make('city')
                            ->label('Stadt')
                            ->maxLength(255),

                        TextInput::make('country')
                            ->label('Land')
                            ->default('Deutschland')
                            ->maxLength(255),
                    ]),

                Section::make('Social Media')
                    ->description('Verlinken Sie Ihre Social-Media-Profile')
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        TextInput::make('linkedin_url')
                            ->label('LinkedIn')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://'),

                        TextInput::make('xing_url')
                            ->label('XING')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://'),

                        TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://'),

                        TextInput::make('facebook_url')
                            ->label('Facebook')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://'),

                        TextInput::make('twitter_url')
                            ->label('X (Twitter)')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://'),

                        TextInput::make('github_url')
                            ->label('GitHub')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://'),
                    ]),

                Section::make('Rechtliche Angaben')
                    ->description('Für Impressum und Rechnungen')
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        TextInput::make('vat_id')
                            ->label('USt-IdNr.')
                            ->maxLength(50)
                            ->placeholder('DE123456789'),

                        TextInput::make('tax_number')
                            ->label('Steuernummer')
                            ->maxLength(50),

                        Textarea::make('imprint_extra')
                            ->label('Zusätzliche Impressums-Angaben')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('z.B. Aufsichtsbehörde, Berufsbezeichnung, etc.'),
                    ]),

                Section::make('SEO Standardwerte')
                    ->description('Standard-Werte für Suchmaschinenoptimierung')
                    ->columns(1)
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        TextInput::make('default_meta_title')
                            ->label('Standard Meta-Titel')
                            ->maxLength(60)
                            ->helperText('Wird verwendet, wenn kein spezifischer Titel gesetzt ist'),

                        Textarea::make('default_meta_description')
                            ->label('Standard Meta-Beschreibung')
                            ->rows(3)
                            ->maxLength(160),
                    ]),

                Section::make('CTA-Box (Call-to-Action)')
                    ->description('Einstellungen für die Kontakt-Box auf Unterseiten')
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        FileUpload::make('cta_image')
                            ->label('Foto')
                            ->image()
                            ->disk('public')
                            ->directory('cta')
                            ->imageEditor()
                            ->columnSpanFull(),

                        TextInput::make('cta_title')
                            ->label('Überschrift')
                            ->maxLength(255)
                            ->placeholder('Ähnliches Projekt geplant?')
                            ->columnSpanFull(),

                        Textarea::make('cta_subtitle')
                            ->label('Beschreibung')
                            ->rows(2)
                            ->maxLength(500)
                            ->placeholder('Ich bespreche gerne Ihr Vorhaben...')
                            ->columnSpanFull(),

                        TextInput::make('cta_name')
                            ->label('Name')
                            ->maxLength(255)
                            ->placeholder('Steffen Fasselt'),

                        TextInput::make('cta_role')
                            ->label('Position / Rolle')
                            ->maxLength(255)
                            ->placeholder('Geschäftsführer'),

                        TextInput::make('cta_button_text')
                            ->label('Primärer Button-Text')
                            ->maxLength(100)
                            ->placeholder('Projekt besprechen'),

                        TextInput::make('cta_secondary_button_text')
                            ->label('Sekundärer Button-Text')
                            ->maxLength(100)
                            ->placeholder('Direkt anrufen'),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Speichern')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $setting = Setting::instance();
        $setting->fill($data);
        $setting->save();

        Notification::make()
            ->title('Einstellungen gespeichert')
            ->success()
            ->send();
    }
}
