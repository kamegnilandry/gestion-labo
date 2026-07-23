<x-layout title="Patients" eyebrow="Accueil & réception">
    <x-slot:headerActions>
        @auth
            @if (auth()->user()->hasRole('receptionniste') || auth()->user()->isAdmin())
                <a href="{{ route('patients.create') }}"
                   class="flex items-center gap-2 rounded-xl bg-accent px-4 py-2.5 text-sm font-semibold text-white shadow-card transition hover:bg-accent-dark">
                    <i data-lucide="plus" class="h-4 w-4"></i> Nouveau patient
                </a>
            @endif
        @endauth
    </x-slot:headerActions>

    <form method="GET" class="mb-6 flex items-center gap-3">
        <div class="relative flex-1 max-w-md">
            <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-inksoft/50"></i>
            <input type="text" name="q" value="{{ $recherche }}" placeholder="Rechercher par nom, code dossier ou téléphone…"
                   class="w-full rounded-xl border border-line bg-white py-2.5 pl-10 pr-3.5 text-sm text-ink placeholder:text-inksoft/40 focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
        </div>
        <button class="rounded-xl border border-line bg-white px-4 py-2.5 text-sm font-medium text-ink hover:bg-bg">Rechercher</button>
    </form>

    @if ($patients->isEmpty())
        <x-empty-state icon="users" title="{{ $recherche ? 'Aucun résultat' : 'Aucun patient enregistré' }}"
                        description="{{ $recherche ? 'Essayez une autre recherche.' : 'Enregistrez votre premier patient pour commencer.' }}" />
    @else
        <div class="overflow-hidden rounded-xl2 border border-line bg-surface shadow-card">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line bg-bg/60 text-[11px] font-semibold uppercase tracking-wide text-inksoft/70">
                        <th class="px-5 py-3">Patient</th>
                        <th class="px-5 py-3">Dossier</th>
                        <th class="px-5 py-3">Sexe / Âge</th>
                        <th class="px-5 py-3">Téléphone</th>
                        <th class="px-5 py-3">Demandes</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($patients as $patient)
                        <tr class="transition hover:bg-bg/50">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-soft font-display text-xs font-semibold text-primary">
                                        {{ mb_strtoupper(mb_substr($patient->prenom, 0, 1).mb_substr($patient->nom, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-ink">{{ $patient->nomComplet() }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-xs text-inksoft">{{ $patient->code_patient }}</td>
                            <td class="px-5 py-3.5 text-inksoft">{{ $patient->sexe }} · {{ $patient->age() }} ans</td>
                            <td class="px-5 py-3.5 font-mono text-xs text-inksoft">{{ $patient->telephone }}</td>
                            <td class="px-5 py-3.5 text-inksoft">{{ $patient->demandes_count }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('patients.show', $patient) }}" class="text-xs font-semibold text-primary hover:underline">Voir le dossier →</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-5">{{ $patients->links() }}</div>
    @endif
</x-layout>
