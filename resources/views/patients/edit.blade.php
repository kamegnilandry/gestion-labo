<x-layout title="Modifier le patient" eyebrow="{{ $patient->code_patient }}">
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('patients.show', $patient) }}" class="mb-5 inline-flex items-center gap-1.5 text-sm text-inksoft hover:text-primary">
            <i data-lucide="arrow-left" class="h-4 w-4"></i> Retour au dossier
        </a>

        <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card sm:p-8">
            <p class="font-display text-lg font-semibold text-ink">Modifier {{ $patient->nomComplet() }}</p>
            <p class="mt-1 font-mono text-xs text-inksoft/70">{{ $patient->code_patient }}</p>

            <form method="POST" action="{{ route('patients.update', $patient) }}" class="mt-6 space-y-5">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">Nom</label>
                        <input type="text" name="nom" value="{{ old('nom', $patient->nom) }}" required
                               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">Prénom</label>
                        <input type="text" name="prenom" value="{{ old('prenom', $patient->prenom) }}" required
                               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">Sexe</label>
                        <div class="flex gap-3">
                            @foreach (['M' => 'Masculin', 'F' => 'Féminin'] as $val => $label)
                                <label class="flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm has-[:checked]:border-primary has-[:checked]:bg-primary-soft has-[:checked]:text-primary">
                                    <input type="radio" name="sexe" value="{{ $val }}" {{ old('sexe', $patient->sexe) === $val ? 'checked' : '' }} required class="accent-primary">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">Date de naissance</label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance', $patient->date_naissance->format('Y-m-d')) }}" required
                               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone', $patient->telephone) }}" required
                               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">E-mail <span class="text-inksoft/50">(optionnel)</span></label>
                        <input type="email" name="email" value="{{ old('email', $patient->email) }}"
                               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-ink">Adresse</label>
                    <input type="text" name="adresse" value="{{ old('adresse', $patient->adresse) }}"
                           class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-card hover:bg-primary-dark">
                        <i data-lucide="save" class="h-4 w-4"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('patients.show', $patient) }}" class="text-sm text-inksoft hover:text-ink">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
