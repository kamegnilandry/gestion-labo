<x-layout title="Utilisateurs & rôles" eyebrow="Administration">
    <x-slot:headerActions>
        <a href="{{ route('utilisateurs.create') }}"
           class="flex items-center gap-2 rounded-xl bg-accent px-4 py-2.5 text-sm font-semibold text-white shadow-card hover:bg-accent-dark">
            <i data-lucide="user-plus" class="h-4 w-4"></i> Nouvel utilisateur
        </a>
    </x-slot:headerActions>

    <div class="overflow-hidden rounded-xl2 border border-line bg-surface shadow-card">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-line bg-bg/60 text-[11px] font-semibold uppercase tracking-wide text-inksoft/70">
                    <th class="px-5 py-3">Utilisateur</th>
                    <th class="px-5 py-3">Rôle</th>
                    <th class="px-5 py-3">Téléphone</th>
                    <th class="px-5 py-3">Statut</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @foreach ($utilisateurs as $u)
                    <tr class="hover:bg-bg/50">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-soft font-display text-xs font-semibold text-primary">
                                    {{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-ink">{{ $u->name }}</p>
                                    <p class="text-xs text-inksoft/60">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-inksoft">{{ $u->roleLabel() }}</td>
                        <td class="px-5 py-3.5 font-mono text-xs text-inksoft">{{ $u->telephone ?: '—' }}</td>
                        <td class="px-5 py-3.5">
                            @if ($u->actif)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-success-soft px-2.5 py-1 text-xs font-semibold text-success">Actif</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-line/60 px-2.5 py-1 text-xs font-semibold text-inksoft">Désactivé</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('utilisateurs.edit', $u) }}" class="text-xs font-semibold text-primary hover:underline">Modifier</a>
                                @if ($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('utilisateurs.destroy', $u) }}" onsubmit="return confirm('Désactiver ce compte ?');">
                                        @csrf @method('DELETE')
                                        <button class="text-xs font-semibold text-danger hover:underline">Désactiver</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
