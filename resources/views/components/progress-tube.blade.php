@props(['demande'])
@php
    $etape = $demande->etape();
    $annulee = $demande->statut === \App\Models\DemandeAnalyse::STATUT_ANNULEE;
    $labels = ['Enregistrée', 'Prélevée', 'Résultats', 'Validée'];
@endphp
<div {{ $attributes->merge(['class' => 'w-full']) }}>
    <div class="flex items-center">
        @for ($i = 1; $i <= 4; $i++)
            @php $rempli = ! $annulee && $i <= $etape; @endphp
            <div class="flex items-center {{ $i < 4 ? 'flex-1' : '' }}">
                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 transition-colors
                            {{ $annulee ? 'border-line bg-line/40' : ($rempli ? 'border-primary bg-primary' : 'border-line bg-white') }}">
                    @if ($rempli)
                        <i data-lucide="check" class="h-3 w-3 text-white"></i>
                    @endif
                </div>
                @if ($i < 4)
                    <div class="mx-1 h-[3px] flex-1 rounded-full {{ ! $annulee && $i < $etape ? 'bg-primary' : 'bg-line' }}"></div>
                @endif
            </div>
        @endfor
    </div>
    <div class="mt-1.5 grid grid-cols-4 text-[10px] font-mono uppercase tracking-wide text-inksoft/60">
        @foreach ($labels as $i => $label)
            <span class="{{ $i === 0 ? 'text-left' : ($i === 3 ? 'text-right' : 'text-center') }}">{{ $label }}</span>
        @endforeach
    </div>
</div>
