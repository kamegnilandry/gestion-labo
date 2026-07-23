<x-layout title="Saisie des résultats" eyebrow="{{ $demande->reference }}">
    <a href="{{ route('demandes.show', $demande) }}" class="mb-5 inline-flex items-center gap-1.5 text-sm text-inksoft hover:text-primary">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Retour à la demande
    </a>

    <div class="mb-6 rounded-xl2 border border-line bg-surface p-5 shadow-card">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-soft font-display text-sm font-semibold text-primary">
                {{ mb_strtoupper(mb_substr($demande->patient->prenom, 0, 1).mb_substr($demande->patient->nom, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-ink">{{ $demande->patient->nomComplet() }}</p>
                <p class="font-mono text-xs text-inksoft/60">{{ $demande->reference }} · {{ $demande->patient->age() }} ans</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('resultats.update', $demande) }}" class="space-y-4">
        @csrf
        @foreach ($demande->lignes as $ligne)
            <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card">
                <div class="flex items-center justify-between">
                    <p class="font-display text-base font-semibold text-ink">{{ $ligne->examen->nom }}</p>
                    <span class="font-mono text-xs text-inksoft/50">{{ $ligne->examen->code }}</span>
                </div>
                @if ($ligne->examen->valeur_reference)
                    <p class="mt-1 text-xs text-inksoft/60">Valeur de référence : {{ $ligne->examen->valeur_reference }} {{ $ligne->examen->unite }}</p>
                @endif

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-inksoft">Valeur mesurée</label>
                        <input type="text" name="resultats[{{ $ligne->id }}][valeur]" value="{{ old("resultats.{$ligne->id}.valeur", $ligne->resultat->valeur ?? '') }}" required
                               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 font-mono text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-inksoft">Unité</label>
                        <input type="text" name="resultats[{{ $ligne->id }}][unite]" value="{{ old("resultats.{$ligne->id}.unite", $ligne->resultat->unite ?? $ligne->examen->unite) }}"
                               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-inksoft">Interprétation</label>
                        <select name="resultats[{{ $ligne->id }}][interpretation]"
                                class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                            <option value="">—</option>
                            @foreach (\App\Models\Resultat::interpretations() as $val => $label)
                                <option value="{{ $val }}" {{ old("resultats.{$ligne->id}.interpretation", $ligne->resultat->interpretation ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="mb-1.5 block text-xs font-medium text-inksoft">Observations <span class="text-inksoft/40">(optionnel)</span></label>
                    <input type="text" name="resultats[{{ $ligne->id }}][observations]" value="{{ old("resultats.{$ligne->id}.observations", $ligne->resultat->observations ?? '') }}"
                           class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                </div>
            </div>
        @endforeach

        <button type="submit" class="flex items-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white shadow-card hover:bg-primary-dark">
            <i data-lucide="save" class="h-4 w-4"></i> Enregistrer les résultats
        </button>
    </form>
</x-layout>
