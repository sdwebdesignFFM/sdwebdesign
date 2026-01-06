<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.settings';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public ?array $data = [];

    public string $activeLocale = 'de';

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
        $this->activeLocale = request()->query('locale', 'de');
        $this->loadFormData();
    }

    protected function loadFormData(): void
    {
        $setting = Setting::instance();

        // Get translatable field names
        $translatableFields = $setting->translatable;

        // Build form data with proper locale values for translatable fields
        $data = [];
        foreach ($setting->getAttributes() as $key => $value) {
            if (in_array($key, $translatableFields)) {
                // Get the translation for the active locale
                $data[$key] = $setting->getTranslation($key, $this->activeLocale, false) ?? '';
            } else {
                $data[$key] = $value;
            }
        }

        // Ensure signature data is loaded even if null
        $data['admin_signature_data'] = $setting->admin_signature_data;
        $data['admin_signer_name'] = $setting->admin_signer_name;
        $data['admin_signer_position'] = $setting->admin_signer_position;

        $this->form->fill($data);
    }

    public function setActiveLocale(string $locale): void
    {
        $this->activeLocale = $locale;
        $this->loadFormData();
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

                        TextInput::make('website_url')
                            ->label('Website')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://www.sdwebdesign.de')
                            ->columnSpanFull(),

                        Textarea::make('imprint_extra')
                            ->label('Zusätzliche Impressums-Angaben')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('z.B. Aufsichtsbehörde, Berufsbezeichnung, etc.'),
                    ]),

                Section::make('Bankverbindung')
                    ->description('Für Rechnungen und Angebote')
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        TextInput::make('bank_name')
                            ->label('Bank')
                            ->maxLength(255)
                            ->placeholder('Sparkasse Frankfurt'),

                        TextInput::make('bank_iban')
                            ->label('IBAN')
                            ->maxLength(34)
                            ->placeholder('DE89 3704 0044 0532 0130 00'),

                        TextInput::make('bank_bic')
                            ->label('BIC')
                            ->maxLength(11)
                            ->placeholder('COBADEFFXXX'),
                    ]),

                Section::make('Zeiterfassung')
                    ->description('Einstellungen für die Arbeitszeiterfassung')
                    ->columns(1)
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        TextInput::make('default_hourly_rate')
                            ->label('Standard-Stundensatz')
                            ->numeric()
                            ->prefix('€')
                            ->default(85.00)
                            ->helperText('Wird verwendet, wenn kein kundenspezifischer Stundensatz hinterlegt ist'),
                    ]),

                Section::make('Allgemeine Geschäftsbedingungen (AGB)')
                    ->description('Diese AGB gelten für alle Angebote und Verträge')
                    ->columns(1)
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        RichEditor::make('agb_content')
                            ->label('AGB Inhalt')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'h2',
                                'h3',
                                'bulletList',
                                'orderedList',
                                'link',
                            ])
                            ->columnSpanFull()
                            ->helperText('Diese AGB werden im Angebots-Annahmeprozess und auf der öffentlichen AGB-Seite angezeigt.'),
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

                Section::make('Unterschrift für Angebote')
                    ->description('Diese Unterschrift wird automatisch für die Gegenzeichnung von Angeboten verwendet')
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        TextInput::make('admin_signer_name')
                            ->label('Name des Unterzeichners')
                            ->maxLength(255)
                            ->placeholder('Steffen Fasselt'),

                        TextInput::make('admin_signer_position')
                            ->label('Position')
                            ->maxLength(255)
                            ->placeholder('Geschäftsführer'),

                        Placeholder::make('signature_info')
                            ->label('Unterschrift')
                            ->content(new HtmlString('
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Die Unterschrift kann im Bereich unten gezeichnet werden.
                                </p>
                            '))
                            ->columnSpanFull(),

                        Hidden::make('admin_signature_data'),
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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('locale_de')
                ->label('DE')
                ->color($this->activeLocale === 'de' ? 'primary' : 'gray')
                ->size('sm')
                ->action(fn () => $this->setActiveLocale('de')),
            Action::make('locale_en')
                ->label('EN')
                ->color($this->activeLocale === 'en' ? 'primary' : 'gray')
                ->size('sm')
                ->action(fn () => $this->setActiveLocale('en')),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Merge signature data from Livewire property (set via Alpine)
        $data['admin_signature_data'] = $this->data['admin_signature_data'] ?? null;

        $setting = Setting::instance();

        // Get translatable field names
        $translatableFields = $setting->translatable;

        // Save each field, using setTranslation for translatable fields
        foreach ($data as $key => $value) {
            if (in_array($key, $translatableFields)) {
                $setting->setTranslation($key, $this->activeLocale, $value);
            } else {
                $setting->{$key} = $value;
            }
        }

        $setting->save();

        Notification::make()
            ->title('Einstellungen gespeichert ('.strtoupper($this->activeLocale).')')
            ->success()
            ->send();
    }
}
