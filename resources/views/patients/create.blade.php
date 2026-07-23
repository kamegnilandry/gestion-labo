<x-layout title="Nouveau patient" eyebrow="Accueil & réception">
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('patients.index') }}" class="mb-5 inline-flex items-center gap-1.5 text-sm text-inksoft hover:text-primary">
            <i data-lucide="arrow-left" class="h-4 w-4"></i> Retour à la liste des patients
        </a>

        <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card sm:p-8">
            <p class="font-display text-lg font-semibold text-ink">Enregistrer un nouveau patient</p>
            <p class="mt-1 text-sm text-inksoft">Un numéro de dossier lui sera attribué automatiquement.</p>

            <form method="POST" action="{{ route('patients.store') }}" class="mt-6 space-y-5">
                @csrf
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">Nom</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required
                               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">Prénom</label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" required
                               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">Sexe</label>
                        <div class="flex gap-3">
                            @foreach (['M' => 'Masculin', 'F' => 'Féminin'] as $val => $label)
                                <label class="flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm has-[:checked]:border-primary has-[:checked]:bg-primary-soft has-[:checked]:text-primary">
                                    <input type="radio" name="sexe" value="{{ $val }}" {{ old('sexe') === $val ? 'checked' : '' }} required class="accent-primary">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">Date de naissance</label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" required
                               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone') }}" required placeholder="6XX XXX XXX"
                               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">E-mail <span class="text-inksoft/50">(optionnel)</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-ink">Adresse</label>
                    <input type="text" name="adresse" value="{{ old('adresse') }}"
                           class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-card hover:bg-primary-dark">
                        <i data-lucide="save" class="h-4 w-4"></i> Enregistrer le patient
                    </button>
                    <a href="{{ route('patients.index') }}" class="text-sm text-inksoft hover:text-ink">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
