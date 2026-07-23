@props(['demande'])
@php
    $colorMap = [
        'ambre' => 'bg-warning-soft text-warning',
        'bleu' => 'bg-primary-soft text-primary-light',
        'violet' => 'bg-violet-soft text-violet',
        'vert' => 'bg-success-soft text-success',
        'rouge' => 'bg-danger-soft text-danger',
        'gris' => 'bg-line/60 text-inksoft',
    ];
    $cls = $colorMap[$demande->statutCouleur()] ?? $colorMap['gris'];
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold $cls"]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
    {{ $demande->statutLabel() }}
</span>
