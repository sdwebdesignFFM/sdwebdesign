@props([
    'type' => 'email', // email or phone
    'value' => '',
    'class' => '',
    'iconClass' => 'w-4 h-4',
    'showIcon' => true,
])

@php
    $id = 'oc-' . uniqid();
    // Encode the value as base64 to hide from simple bots
    $encoded = base64_encode($value);
    $cleanValue = $type === 'phone' ? preg_replace('/[^0-9+]/', '', $value) : $value;
    $encodedClean = base64_encode($cleanValue);
@endphp

<a
    id="{{ $id }}"
    href="#"
    {{ $attributes->merge(['class' => $class]) }}
    data-t="{{ $type }}"
    data-v="{{ $encoded }}"
    data-c="{{ $encodedClean }}"
>
    @if($showIcon)
        @if($type === 'email')
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClass }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            </svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconClass }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
        @endif
    @endif
    <span class="oc-text">...</span>
</a>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-v]').forEach(function(el) {
        const type = el.dataset.t;
        const value = atob(el.dataset.v);
        const clean = atob(el.dataset.c);
        const prefix = type === 'email' ? 'mailto:' : 'tel:';

        el.href = prefix + clean;
        el.querySelector('.oc-text').textContent = value;

        // Clean up data attributes
        delete el.dataset.v;
        delete el.dataset.c;
        delete el.dataset.t;
    });
});
</script>
@endpush
@endonce
