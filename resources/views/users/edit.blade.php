<x-layout title="Modifier l'utilisateur" eyebrow="Administration">
    <div class="mx-auto max-w-xl">
        <a href="{{ route('utilisateurs.index') }}" class="mb-5 inline-flex items-center gap-1.5 text-sm text-inksoft hover:text-primary">
            <i data-lucide="arrow-left" class="h-4 w-4"></i> Retour à la liste
        </a>
        <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card sm:p-8">
            <p class="font-display text-lg font-semibold text-ink">Modifier {{ $user->name }}</p>
            <form method="POST" action="{{ route('utilisateurs.update', $user) }}" class="mt-6 space-y-5">
                @csrf
                @method('PUT')
                @include('users._form')
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-card hover:bg-primary-dark">
                        <i data-lucide="save" class="h-4 w-4"></i> Enregistrer
                    </button>
                    <a href="{{ route('utilisateurs.index') }}" class="text-sm text-inksoft hover:text-ink">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
