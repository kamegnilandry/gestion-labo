<x-layout title="Demandes d'analyses" eyebrow="Suivi des analyses">
    <x-slot:headerActions>
        @auth
            @if (auth()->user()->hasRole('receptionniste') || auth()->user()->isAdmin())
                <a href="{{ route('demandes.create') }}"
                   class="flex items-center gap-2 rounded-xl bg-accent px-4 py-2.5 text-sm font-semibold text-white shadow-card hover:bg-accent-dark">
                    <i data-lucide="plus" class="h-4 w-4"></i> Nouvelle demande
                </a>
            @endif
        @endauth
    </x-slot:headerActions>

    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('demandes.index') }}"
           class="rounded-full px-3.5 py-1.5 text-xs font-semibold transition {{ ! $statut ? 'bg-primary text-white' : 'bg-white text-inksoft border border-line hover:border-primary/40' }}">
            Toutes <span class="opacity-70">({{ $compteurs->sum() }})</span>
        </a>
        @foreach (\App\Models\DemandeAnalyse::statuts() as $key => $label)
            <a href="{{ route('demandes.index', ['statut' => $key]) }}"
               class="rounded-full px-3.5 py-1.5 text-xs font-semibold transition {{ $statut === $key ? 'bg-primary text-white' : 'bg-white text-inksoft border border-line hover:border-primary/40' }}">
                {{ $label }} <span class="opacity-70">({{ $compteurs[$key] ?? 0 }})</span>
            </a>
        @endforeach
    </div>

    @if ($demandes->isEmpty())
        <x-empty-state icon="flask-conical" title="Aucune demande trouvée" description="Les demandes correspondant à ce filtre apparaîtront ici." />
    @else
        <div class="space-y-3">
            @foreach ($demandes as $demande)
                <a href="{{ route('demandes.show', $demande) }}"
                   class="block rounded-xl2 border border-line bg-surface p-5 shadow-card transition hover:border-primary/30 hover:shadow-pop">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-soft font-display text-sm font-semibold text-primary">
                                {{ mb_strtoupper(mb_substr($demande->patient->prenom, 0, 1).mb_substr($demande->patient->nom, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-ink">{{ $demande->patient->nomComplet() }}</p>
                                <p class="mt-0.5 font-mono text-xs text-inksoft/70">{{ $demande->reference }}</p>
                                <p class="mt-1 text-xs text-inksoft/70">{{ $demande->lignes->pluck('examen.nom')->implode(', ') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:flex-col sm:items-end">
                            <x-badge-statut :demande="$demande" />
                            <span class="text-xs text-inksoft/60">{{ $demande->date_demande->diffForHumans() }}</span>
                        </div>
                    </div>
                    <x-progress-tube :demande="$demande" class="mt-4 max-w-sm" />
                </a>
            @endforeach
        </div>
        <div class="mt-5">{{ $demandes->links() }}</div>
    @endif
</x-layout>
