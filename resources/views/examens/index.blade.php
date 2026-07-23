<x-layout title="Catalogue des examens" eyebrow="Analyses médicales">
    <x-slot:headerActions>
        @auth
            @if (auth()->user()->hasRole('biologiste') || auth()->user()->isAdmin())
                <a href="{{ route('examens.create') }}"
                   class="flex items-center gap-2 rounded-xl bg-accent px-4 py-2.5 text-sm font-semibold text-white shadow-card hover:bg-accent-dark">
                    <i data-lucide="plus" class="h-4 w-4"></i> Ajouter un examen
                </a>
            @endif
        @endauth
    </x-slot:headerActions>

    @if ($examens->isEmpty())
        <x-empty-state icon="list-checks" title="Catalogue vide" description="Ajoutez les analyses proposées par votre laboratoire." />
    @else
        <div class="space-y-8">
            @foreach ($examens as $categorie => $liste)
                <div>
                    <p class="mb-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-inksoft/60">{{ $categorie ?: 'Sans catégorie' }}</p>
                    <div class="overflow-hidden rounded-xl2 border border-line bg-surface shadow-card">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-line bg-bg/60 text-[11px] font-semibold uppercase tracking-wide text-inksoft/70">
                                    <th class="px-5 py-3">Code</th>
                                    <th class="px-5 py-3">Examen</th>
                                    <th class="px-5 py-3">Unité</th>
                                    <th class="px-5 py-3">Valeur de référence</th>
                                    <th class="px-5 py-3">Prix</th>
                                    <th class="px-5 py-3">Statut</th>
                                    <th class="px-5 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @foreach ($liste as $examen)
                                    <tr class="hover:bg-bg/50">
                                        <td class="px-5 py-3 font-mono text-xs font-semibold text-primary">{{ $examen->code }}</td>
                                        <td class="px-5 py-3 font-medium text-ink">{{ $examen->nom }}</td>
                                        <td class="px-5 py-3 text-inksoft">{{ $examen->unite ?: '—' }}</td>
                                        <td class="px-5 py-3 font-mono text-xs text-inksoft">{{ $examen->valeur_reference ?: '—' }}</td>
                                        <td class="px-5 py-3 text-inksoft">{{ $examen->prix ? number_format($examen->prix, 0, ',', ' ').' FCFA' : '—' }}</td>
                                        <td class="px-5 py-3">
                                            @if ($examen->actif)
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-success-soft px-2.5 py-1 text-xs font-semibold text-success">Actif</span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-line/60 px-2.5 py-1 text-xs font-semibold text-inksoft">Inactif</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            @auth
                                                @if (auth()->user()->hasRole('biologiste') || auth()->user()->isAdmin())
                                                    <div class="flex items-center justify-end gap-3">
                                                        <a href="{{ route('examens.edit', $examen) }}" class="text-xs font-semibold text-primary hover:underline">Modifier</a>
                                                        <form method="POST" action="{{ route('examens.destroy', $examen) }}" onsubmit="return confirm('Supprimer ou désactiver cet examen ?');">
                                                            @csrf @method('DELETE')
                                                            <button class="text-xs font-semibold text-danger hover:underline">Retirer</button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endauth
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layout>
