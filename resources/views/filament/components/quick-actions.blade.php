<div class="flex items-center gap-2 me-4">
    <a href="{{ route('filament.admin.resources.work-logs.index') }}?tableAction=create"
       class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-white/5"
       title="Neue Arbeitszeit erfassen">
        <x-heroicon-o-clock class="h-5 w-5" />
        <span class="hidden sm:inline">+Zeit</span>
    </a>
    <a href="{{ route('filament.admin.resources.tasks.create') }}"
       class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-white/5"
       title="Neue Aufgabe erstellen">
        <x-heroicon-o-clipboard-document-list class="h-5 w-5" />
        <span class="hidden sm:inline">+Aufgabe</span>
    </a>
</div>
