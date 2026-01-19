<x-layouts.frontend>
    @section('title', $quote->title . ' - Angebot ' . $quote->quote_number)

    <div class="min-h-screen bg-muted pt-20">
        {{-- Livewire Quote Acceptance Component --}}
        <livewire:quote-acceptance :quote="$quote" />
    </div>
</x-layouts.frontend>
