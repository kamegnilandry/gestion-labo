<x-layout title="Enregistrer le prélèvement" eyebrow="{{ $demande->reference }}">
    <a href="{{ route('demandes.show', $demande) }}" class="mb-5 inline-flex items-center gap-1.5 text-sm text-inksoft hover:text-primary">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Retour à la demande
    </a>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card sm:p-8">
                <div class="flex items-center gap-2 text-accent">
                    <i data-lucide="droplet" class="h-5 w-5"></i>
                    <p class="font-display text-lg font-semibold text-ink">Enregistrer le prélèvement</p>
                </div>
                <p class="mt-1 text-sm text-inksoft">Pour {{ $demande->patient->nomComplet() }} · {{ $demande->lignes->count() }} analyse(s) demandée(s)</p>

                <form method="POST" action="{{ route('prelevements.store', $demande) }}" class="mt-6 space-y-5">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">Type d'échantillon</label>
                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                            @foreach ($types as $type)
                                <label class="flex cursor-pointer items-center justify-center rounded-xl border border-line bg-white px-3 py-2.5 text-center text-sm has-[:checked]:border-primary has-[:checked]:bg-primary-soft has-[:checked]:text-primary">
                                    <input type="radio" name="type_echantillon" value="{{ $type }}" required class="sr-only">
                                    {{ $type }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">Date et heure du prélèvement</label>
                        <input type="datetime-local" name="date_prelevement" value="{{ old('date_prelevement', now()->format('Y-m-d\TH:i')) }}" required
                               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">Notes <span class="text-inksoft/50">(optionnel)</span></label>
                        <textarea name="notes" rows="3" class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit" class="flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-card hover:bg-primary-dark">
                        <i data-lucide="check" class="h-4 w-4"></i> Confirmer le prélèvement
                    </button>
                </form>
            </div>
        </div>

        <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card">
            <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-inksoft/60">Analyses concernées</p>
            <ul class="mt-3 space-y-2">
                @foreach ($demande->lignes as $ligne)
                    <li class="flex items-center gap-2 text-sm text-ink">
                        <i data-lucide="flask-conical" class="h-3.5 w-3.5 text-primary/60"></i> {{ $ligne->examen->nom }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-layout>
