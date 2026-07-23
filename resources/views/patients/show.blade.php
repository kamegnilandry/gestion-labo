<x-layout title="{{ $patient->nomComplet() }}" eyebrow="Dossier patient · {{ $patient->code_patient }}">
    <x-slot:headerActions>
        @auth
            @if (auth()->user()->hasRole('receptionniste') || auth()->user()->isAdmin())
                <a href="{{ route('patients.edit', $patient) }}"
                   class="flex items-center gap-2 rounded-xl border border-line bg-white px-4 py-2.5 text-sm font-medium text-ink hover:bg-bg">
                    <i data-lucide="pencil" class="h-4 w-4"></i> Modifier
                </a>
                <a href="{{ route('demandes.create', ['patient_id' => $patient->id]) }}"
                   class="flex items-center gap-2 rounded-xl bg-accent px-4 py-2.5 text-sm font-semibold text-white shadow-card hover:bg-accent-dark">
                    <i data-lucide="plus" class="h-4 w-4"></i> Nouvelle demande
                </a>
            @endif
        @endauth
    </x-slot:headerActions>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card lg:col-span-1">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-primary-soft font-display text-xl font-semibold text-primary">
                    {{ mb_strtoupper(mb_substr($patient->prenom, 0, 1).mb_substr($patient->nom, 0, 1)) }}
                </div>
                <div>
                    <p class="font-display text-lg font-semibold text-ink">{{ $patient->nomComplet() }}</p>
                    <p class="font-mono text-xs text-inksoft/70">{{ $patient->code_patient }}</p>
                </div>
            </div>

            <dl class="mt-6 space-y-4 text-sm">
                <div class="flex items-center justify-between border-b border-line pb-3">
                    <dt class="text-inksoft/70">Sexe</dt>
                    <dd class="font-medium text-ink">{{ $patient->sexe === 'M' ? 'Masculin' : 'Féminin' }}</dd>
                </div>
                <div class="flex items-center justify-between border-b border-line pb-3">
                    <dt class="text-inksoft/70">Date de naissance</dt>
                    <dd class="font-medium text-ink">{{ $patient->date_naissance->format('d/m/Y') }} ({{ $patient->age() }} ans)</dd>
                </div>
                <div class="flex items-center justify-between border-b border-line pb-3">
                    <dt class="text-inksoft/70">Téléphone</dt>
                    <dd class="font-mono text-xs font-medium text-ink">{{ $patient->telephone }}</dd>
                </div>
                @if ($patient->email)
                <div class="flex items-center justify-between border-b border-line pb-3">
                    <dt class="text-inksoft/70">E-mail</dt>
                    <dd class="font-medium text-ink">{{ $patient->email }}</dd>
                </div>
                @endif
                <div class="flex items-center justify-between">
                    <dt class="text-inksoft/70">Adresse</dt>
                    <dd class="text-right font-medium text-ink">{{ $patient->adresse ?: '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="lg:col-span-2">
            <p class="mb-4 font-display text-lg font-semibold text-ink">Historique des analyses</p>

            @if ($patient->demandes->isEmpty())
                <x-empty-state icon="flask-conical" title="Aucune demande d'analyse"
                                description="L'historique des analyses de ce patient apparaîtra ici." />
            @else
                <div class="space-y-3">
                    @foreach ($patient->demandes as $demande)
                        <a href="{{ route('demandes.show', $demande) }}"
                           class="block rounded-xl2 border border-line bg-surface p-5 shadow-card transition hover:border-primary/30 hover:shadow-pop">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-mono text-xs text-inksoft/70">{{ $demande->reference }}</p>
                                    <p class="mt-1 text-sm font-medium text-ink">
                                        {{ $demande->lignes->pluck('examen.nom')->implode(', ') }}
                                    </p>
                                    <p class="mt-1 text-xs text-inksoft/60">{{ $demande->date_demande->format('d/m/Y à H:i') }}</p>
                                </div>
                                <x-badge-statut :demande="$demande" />
                            </div>
                            <x-progress-tube :demande="$demande" class="mt-4" />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layout>
