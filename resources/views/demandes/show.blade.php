<x-layout title="{{ $demande->reference }}" eyebrow="Demande d'analyse">
    <x-slot:headerActions>
        @auth
            @if ($demande->statut === \App\Models\DemandeAnalyse::STATUT_ENREGISTREE && (auth()->user()->hasRole('technicien') || auth()->user()->isAdmin()))
                <a href="{{ route('prelevements.create', $demande) }}"
                   class="flex items-center gap-2 rounded-xl bg-accent px-4 py-2.5 text-sm font-semibold text-white shadow-card hover:bg-accent-dark">
                    <i data-lucide="droplet" class="h-4 w-4"></i> Enregistrer le prélèvement
                </a>
            @endif
            @if (in_array($demande->statut, [\App\Models\DemandeAnalyse::STATUT_PRELEVEE, \App\Models\DemandeAnalyse::STATUT_RESULTATS_SAISIS]) && (auth()->user()->hasRole('technicien') || auth()->user()->isAdmin()))
                <a href="{{ route('resultats.edit', $demande) }}"
                   class="flex items-center gap-2 rounded-xl bg-accent px-4 py-2.5 text-sm font-semibold text-white shadow-card hover:bg-accent-dark">
                    <i data-lucide="clipboard-edit" class="h-4 w-4"></i> Saisir les résultats
                </a>
            @endif
            @if ($demande->statut === \App\Models\DemandeAnalyse::STATUT_RESULTATS_SAISIS && (auth()->user()->hasRole('biologiste') || auth()->user()->isAdmin()))
                <form method="POST" action="{{ route('demandes.valider', $demande) }}" onsubmit="return confirm('Valider définitivement ces résultats ?');">
                    @csrf
                    <button class="flex items-center gap-2 rounded-xl bg-success px-4 py-2.5 text-sm font-semibold text-white shadow-card hover:opacity-90">
                        <i data-lucide="badge-check" class="h-4 w-4"></i> Valider les résultats
                    </button>
                </form>
            @endif
            @if ($demande->statut === \App\Models\DemandeAnalyse::STATUT_VALIDEE)
                <a href="{{ route('rapports.show', $demande) }}" target="_blank"
                   class="flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-card hover:bg-primary-dark">
                    <i data-lucide="file-down" class="h-4 w-4"></i> Compte-rendu PDF
                </a>
            @endif
        @endauth
    </x-slot:headerActions>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <!-- Progression -->
            <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card">
                <div class="flex items-center justify-between">
                    <p class="font-mono text-xs text-inksoft/70">{{ $demande->reference }}</p>
                    <x-badge-statut :demande="$demande" />
                </div>
                <x-progress-tube :demande="$demande" class="mt-5" />
            </div>

            <!-- Analyses & résultats -->
            <div class="rounded-xl2 border border-line bg-surface shadow-card">
                <div class="border-b border-line p-6 pb-4">
                    <p class="font-display text-base font-semibold text-ink">Analyses demandées</p>
                </div>
                <div class="divide-y divide-line">
                    @foreach ($demande->lignes as $ligne)
                        <div class="p-6 py-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-medium text-ink">{{ $ligne->examen->nom }}
                                        <span class="ml-1.5 font-mono text-xs text-inksoft/50">{{ $ligne->examen->code }}</span>
                                    </p>
                                    @if ($ligne->resultat)
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            <span class="rounded-lg bg-bg px-2.5 py-1 font-mono text-sm font-semibold text-ink">
                                                {{ $ligne->resultat->valeur }} {{ $ligne->resultat->unite }}
                                            </span>
                                            @if ($ligne->resultat->valeur_reference)
                                                <span class="text-xs text-inksoft/60">Réf. {{ $ligne->resultat->valeur_reference }}</span>
                                            @endif
                                            @if ($ligne->resultat->interpretation)
                                                @php
                                                    $tone = ['normal' => 'text-success bg-success-soft', 'anormal' => 'text-warning bg-warning-soft', 'critique' => 'text-danger bg-danger-soft'][$ligne->resultat->interpretation] ?? 'text-inksoft bg-line/50';
                                                @endphp
                                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $tone }}">{{ $ligne->resultat->interpretationLabel() }}</span>
                                            @endif
                                        </div>
                                        @if ($ligne->resultat->observations)
                                            <p class="mt-1.5 text-xs italic text-inksoft/70">« {{ $ligne->resultat->observations }} »</p>
                                        @endif
                                    @else
                                        <p class="mt-1.5 text-xs text-inksoft/50">Résultat non encore saisi</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($demande->notes)
                <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card">
                    <p class="font-display text-base font-semibold text-ink">Notes</p>
                    <p class="mt-2 text-sm text-inksoft">{{ $demande->notes }}</p>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <!-- Patient -->
            <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-inksoft/60">Patient</p>
                <a href="{{ route('patients.show', $demande->patient) }}" class="mt-3 flex items-center gap-3 group">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-primary-soft font-display text-sm font-semibold text-primary">
                        {{ mb_strtoupper(mb_substr($demande->patient->prenom, 0, 1).mb_substr($demande->patient->nom, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-ink group-hover:text-primary">{{ $demande->patient->nomComplet() }}</p>
                        <p class="font-mono text-xs text-inksoft/60">{{ $demande->patient->code_patient }}</p>
                    </div>
                </a>
                <dl class="mt-4 space-y-2 border-t border-line pt-4 text-xs">
                    <div class="flex justify-between"><dt class="text-inksoft/60">Âge</dt><dd class="text-ink">{{ $demande->patient->age() }} ans</dd></div>
                    <div class="flex justify-between"><dt class="text-inksoft/60">Téléphone</dt><dd class="font-mono text-ink">{{ $demande->patient->telephone }}</dd></div>
                </dl>
            </div>

            <!-- Chronologie -->
            <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-inksoft/60">Chronologie</p>
                <ul class="mt-4 space-y-4 text-sm">
                    <li class="flex gap-3">
                        <i data-lucide="file-plus" class="mt-0.5 h-4 w-4 shrink-0 text-primary"></i>
                        <div>
                            <p class="text-ink">Demande créée</p>
                            <p class="text-xs text-inksoft/60">{{ $demande->creePar?->name ?? '—' }} · {{ $demande->date_demande->format('d/m/Y H:i') }}</p>
                        </div>
                    </li>
                    @if ($demande->prelevement)
                        <li class="flex gap-3">
                            <i data-lucide="droplet" class="mt-0.5 h-4 w-4 shrink-0 text-primary"></i>
                            <div>
                                <p class="text-ink">Prélèvement — {{ $demande->prelevement->type_echantillon }}</p>
                                <p class="text-xs text-inksoft/60">{{ $demande->prelevement->technicien?->name ?? '—' }} · {{ $demande->prelevement->date_prelevement->format('d/m/Y H:i') }}</p>
                            </div>
                        </li>
                    @endif
                    @if ($demande->statut === \App\Models\DemandeAnalyse::STATUT_VALIDEE)
                        <li class="flex gap-3">
                            <i data-lucide="badge-check" class="mt-0.5 h-4 w-4 shrink-0 text-success"></i>
                            <div>
                                <p class="text-ink">Résultats validés</p>
                                <p class="text-xs text-inksoft/60">{{ $demande->valideePar?->name ?? '—' }} · {{ $demande->validee_at?->format('d/m/Y H:i') }}</p>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>

            @auth
                @if (! in_array($demande->statut, [\App\Models\DemandeAnalyse::STATUT_VALIDEE, \App\Models\DemandeAnalyse::STATUT_ANNULEE]) && (auth()->user()->hasRole(['receptionniste', 'biologiste']) || auth()->user()->isAdmin()))
                    <form method="POST" action="{{ route('demandes.annuler', $demande) }}" onsubmit="return confirm('Annuler cette demande ?');">
                        @csrf
                        <button class="flex w-full items-center justify-center gap-2 rounded-xl border border-danger/20 bg-danger-soft px-4 py-2.5 text-sm font-semibold text-danger hover:bg-danger/10">
                            <i data-lucide="x-circle" class="h-4 w-4"></i> Annuler la demande
                        </button>
                    </form>
                @endif
            @endauth
        </div>
    </div>
</x-layout>
