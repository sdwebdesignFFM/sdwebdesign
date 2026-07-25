<?php

namespace App\Filament\Pages;

use App\Models\Page as PageModel;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Contracts\TranslatableContentDriver;
use Illuminate\Support\Arr;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\SpatieTranslatableContentDriver;

abstract class EditSinglePage extends Page
{
    protected string $view = 'filament.pages.edit-single-page';

    public ?string $activeLocale = null;

    protected ?string $oldActiveLocale = null;

    /**
     * @var array<string, array<string, mixed>>
     */
    public array $otherLocaleData = [];

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    abstract protected static function getPageType(): string;

    /**
     * @return array<Tab>
     */
    abstract protected function getContentTabs(): array;

    public function mount(): void
    {
        $this->activeLocale = $this->getStoredActiveLocale() ?? $this->getDefaultLocale();
        $this->form->fill($this->getRecord()?->attributesToArray());
    }

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }

    /**
     * @return array<string>
     */
    public function getTranslatableLocales(): array
    {
        return ['de', 'en'];
    }

    public function getDefaultLocale(): string
    {
        return 'de';
    }

    public function getActiveSchemaLocale(): ?string
    {
        if (! in_array($this->activeLocale, $this->getTranslatableLocales(), true)) {
            return null;
        }

        return $this->activeLocale;
    }

    public function getActiveActionsLocale(): ?string
    {
        return $this->activeLocale;
    }

    /**
     * @return class-string<TranslatableContentDriver>|null
     */
    public function getFilamentTranslatableContentDriver(): ?string
    {
        return SpatieTranslatableContentDriver::class;
    }

    public function updatingActiveLocale(): void
    {
        $this->oldActiveLocale = $this->activeLocale;
    }

    public function updatedActiveLocale(): void
    {
        if (filament('spatie-translatable')->getPersistLocale()) {
            session()->put('spatie_translatable_active_locale', $this->activeLocale);
        }

        if (blank($this->oldActiveLocale)) {
            return;
        }

        $this->resetValidation();

        $translatableAttributes = $this->getTranslatableAttributes();

        $this->otherLocaleData[$this->oldActiveLocale] = Arr::only(
            $this->form->getState(),
            $translatableAttributes
        );

        $this->form->fill([
            ...Arr::except(
                $this->form->getState(),
                $translatableAttributes
            ),
            ...$this->otherLocaleData[$this->activeLocale] ?? [],
        ]);

        unset($this->otherLocaleData[$this->activeLocale]);
    }

    protected function getStoredActiveLocale(): ?string
    {
        if (! filament('spatie-translatable')->getPersistLocale()) {
            return null;
        }

        $locale = session()->get('spatie_translatable_active_locale');

        if ($locale && in_array($locale, $this->getTranslatableLocales(), true)) {
            return $locale;
        }

        return null;
    }

    /**
     * @return array<string>
     */
    public function getTranslatableAttributes(): array
    {
        $record = $this->getRecord();

        if (! $record) {
            return ['title', 'content', 'meta_title', 'meta_description'];
        }

        return $record->getTranslatableAttributes();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Tabs::make('Tabs')
                        ->tabs($this->getContentTabs())
                        ->persistTabInQueryString(),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Speichern')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $record = $this->getRecord();

        if (! $record) {
            $record = new PageModel;
            $record->type = static::getPageType();
        }

        $translatableAttributes = $this->getTranslatableAttributes();

        // Set non-translatable attributes
        $record->fill(Arr::except($data, $translatableAttributes));

        // Set translatable attributes for current locale
        foreach (Arr::only($data, $translatableAttributes) as $key => $value) {
            $record->setTranslation($key, $this->activeLocale, $value);
        }

        // Set translatable attributes for other locales
        foreach ($this->otherLocaleData as $locale => $localeData) {
            foreach (Arr::only($localeData, $translatableAttributes) as $key => $value) {
                $record->setTranslation($key, $locale, $value);
            }
        }

        $record->save();

        Notification::make()
            ->success()
            ->title('Gespeichert')
            ->send();
    }

    public function getRecord(): ?PageModel
    {
        return PageModel::query()
            ->where('type', static::getPageType())
            ->first();
    }
}
