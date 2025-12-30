<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Vorschau')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn () => $this->getPreviewUrl())
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getPreviewUrl(): string
    {
        $page = $this->getRecord();

        return match ($page->type) {
            'home' => route('home'),
            'solutions' => route('solutions'),
            'solution-detail' => route('solutions.show', $page->slug),
            'references' => route('references'),
            'about' => route('about'),
            'contact' => route('contact'),
            'imprint' => route('imprint'),
            'privacy' => route('privacy'),
            default => '/',
        };
    }
}
