@props(['label', 'value', 'icon', 'tint' => 'primary', 'hint' => null])
@php
    $tints = [
        'primary' => 'bg-primary-soft text-primary',
        'accent' => 'bg-accent-soft text-accent',
        'success' => 'bg-success-soft text-success',
        'warning' => 'bg-warning-soft text-warning',
    ];
@endphp
<div {{ $attributes->merge(['class' => 'rounded-xl2 border border-line bg-surface p-5 shadow-card']) }}>
    <div class="flex items-start justify-between">
        <div>
            <p class="text-[11.5px] font-medium uppercase tracking-[0.08em] text-inksoft/70">{{ $label }}</p>
            <p class="mt-2 font-display text-3xl font-semibold text-ink">{{ $value }}</p>
            @if ($hint)
                <p class="mt-1 text-xs text-inksoft/70">{{ $hint }}</p>
            @endif
        </div>
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $tints[$tint] ?? $tints['primary'] }}">
            <i data-lucide="{{ $icon }}" class="h-5 w-5"></i>
        </div>
    </div>
</div>
