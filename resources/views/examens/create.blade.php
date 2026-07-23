<x-layout title="Nouvel examen" eyebrow="Catalogue">
    <div class="mx-auto max-w-xl">
        <a href="{{ route('examens.index') }}" class="mb-5 inline-flex items-center gap-1.5 text-sm text-inksoft hover:text-primary">
            <i data-lucide="arrow-left" class="h-4 w-4"></i> Retour au catalogue
        </a>
        <div class="rounded-xl2 border border-line bg-surface p-6 shadow-card sm:p-8">
            <p class="font-display text-lg font-semibold text-ink">Ajouter un examen au catalogue</p>
            <form method="POST" action="{{ route('examens.store') }}" class="mt-6 space-y-5">
                @csrf
                @include('examens._form')
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-card hover:bg-primary-dark">
                        <i data-lucide="save" class="h-4 w-4"></i> Ajouter au catalogue
                    </button>
                    <a href="{{ route('examens.index') }}" class="text-sm text-inksoft hover:text-ink">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
