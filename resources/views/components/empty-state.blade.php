@props(['icon' => 'inbox', 'title', 'description' => null])
<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center rounded-xl2 border border-dashed border-line bg-white/60 px-6 py-14 text-center']) }}>
    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary-soft text-primary">
        <i data-lucide="{{ $icon }}" class="h-5 w-5"></i>
    </div>
    <p class="mt-4 font-display text-base font-semibold text-ink">{{ $title }}</p>
    @if ($description)
        <p class="mt-1.5 max-w-sm text-sm text-inksoft/80">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-5">{{ $action }}</div>
    @endisset
</div>
