<x-layout title="Nouvelle demande d'analyse" eyebrow="Analyses médicales">
    <a href="{{ route('demandes.index') }}" class="mb-5 inline-flex items-center gap-1.5 text-sm text-inksoft hover:text-primary">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Retour aux demandes
    </a>

    <form method="POST" action="{{ route('demandes.store') }}"
          x-data='{
              patients: {!! \Illuminate\Support\Js::from($patients->map(fn($p) => ["id" => $p->id, "label" => $p->nomComplet(), "code" => $p->code_patient, "tel" => $p->telephone])) !!},
              search: "",
              selectedPatient: {{ $patientPreselectionne ?: "null" }},
              selectedCount: 0,
              get filtered() {
                  if (! this.search) return this.patients;
                  const s = this.search.toLowerCase();
                  return this.patients.filter(p => p.label.toLowerCase().includes(s) || p.code.toLowerCase().includes(s));
              }
          }'
          class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        @csrf

        <div class="space-y-6 lg:col-span-2">
            <!-- Sélection du patient -->
            <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card">
                <p class="font-display text-base font-semibold text-ink">1. Sélectionner le patient</p>
                <div class="relative mt-4">
                    <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-inksoft/50"></i>
                    <input type="text" x-model="search" placeholder="Rechercher un patient par nom ou code…"
                           class="w-full rounded-xl border border-line bg-white py-2.5 pl-10 pr-3.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                </div>
                <div class="mt-3 max-h-64 space-y-1.5 overflow-y-auto scrollbar-thin pr-1">
                    <template x-for="p in filtered" :key="p.id">
                        <label class="flex cursor-pointer items-center justify-between rounded-xl border px-3.5 py-2.5 text-sm transition"
                               :class="selectedPatient === p.id ? 'border-primary bg-primary-soft' : 'border-line bg-white hover:border-primary/30'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="patient_id" :value="p.id" x-model.number="selectedPatient" required class="accent-primary">
                                <div>
                                    <span x-text="p.label" class="font-medium text-ink"></span>
                                    <span class="ml-2 font-mono text-xs text-inksoft/60" x-text="p.code"></span>
                                </div>
                            </div>
                            <span class="font-mono text-xs text-inksoft/50" x-text="p.tel"></span>
                        </label>
                    </template>
                    <p x-show="filtered.length === 0" class="py-4 text-center text-sm text-inksoft/60">Aucun patient trouvé.</p>
                </div>
                <p class="mt-3 text-xs text-inksoft/60">
                    Patient introuvable ? <a href="{{ route('patients.create') }}" class="font-semibold text-primary hover:underline">Créer un nouveau dossier</a>
                </p>
            </div>

            <!-- Sélection des analyses -->
            <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card">
                <div class="flex items-center justify-between">
                    <p class="font-display text-base font-semibold text-ink">2. Choisir les analyses demandées</p>
                    <span class="rounded-full bg-primary-soft px-2.5 py-1 text-xs font-semibold text-primary" x-text="selectedCount + ' sélectionnée(s)'"></span>
                </div>
                <div class="mt-4 space-y-5">
                    @foreach ($examens as $categorie => $liste)
                        <div>
                            <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.1em] text-inksoft/60">{{ $categorie ?: 'Autres' }}</p>
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                @foreach ($liste as $examen)
                                    <label class="flex cursor-pointer items-center justify-between gap-2 rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm transition has-[:checked]:border-primary has-[:checked]:bg-primary-soft">
                                        <span class="flex items-center gap-2.5">
                                            <input type="checkbox" name="examens[]" value="{{ $examen->id }}" @change="selectedCount += $event.target.checked ? 1 : -1" class="h-4 w-4 rounded border-line text-primary focus:ring-primary/30">
                                            <span class="text-ink">{{ $examen->nom }}</span>
                                        </span>
                                        <span class="font-mono text-[11px] text-inksoft/50">{{ $examen->code }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card">
                <p class="font-display text-base font-semibold text-ink">3. Notes complémentaires</p>
                <textarea name="notes" rows="4" placeholder="Contexte clinique, urgence, instructions particulières…"
                          class="mt-3 w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">{{ old('notes') }}</textarea>
            </div>

            <div class="sticky top-24 rounded-xl2 border border-line bg-primary p-6 text-white shadow-card">
                <p class="font-display text-base font-semibold">Récapitulatif</p>
                <p class="mt-2 text-sm text-white/70">
                    <span x-text="selectedCount"></span> analyse(s) sélectionnée(s) pour
                    <span x-text="selectedPatient ? patients.find(p => p.id === selectedPatient)?.label : 'aucun patient'"></span>.
                </p>
                <button type="submit" :disabled="!selectedPatient || selectedCount === 0"
                        class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-accent px-4 py-3 text-sm font-semibold text-white transition hover:bg-accent-dark disabled:cursor-not-allowed disabled:opacity-40">
                    <i data-lucide="send" class="h-4 w-4"></i> Enregistrer la demande
                </button>
            </div>
        </div>
    </form>
</x-layout>
