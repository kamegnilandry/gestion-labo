@php $examen = $examen ?? null; @endphp
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-1.5 block text-sm font-medium text-ink">Code</label>
        <input type="text" name="code" value="{{ old('code', $examen?->code) }}" required placeholder="Ex : NFS"
               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 font-mono text-sm uppercase focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
    </div>
    <div>
        <label class="mb-1.5 block text-sm font-medium text-ink">Catégorie</label>
        <input type="text" name="categorie" value="{{ old('categorie', $examen?->categorie) }}" placeholder="Ex : Biochimie"
               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
    </div>
</div>
<div>
    <label class="mb-1.5 block text-sm font-medium text-ink">Nom de l'examen</label>
    <input type="text" name="nom" value="{{ old('nom', $examen?->nom) }}" required
           class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
</div>
<div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
    <div>
        <label class="mb-1.5 block text-sm font-medium text-ink">Unité</label>
        <input type="text" name="unite" value="{{ old('unite', $examen?->unite) }}" placeholder="g/L"
               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
    </div>
    <div>
        <label class="mb-1.5 block text-sm font-medium text-ink">Valeur de référence</label>
        <input type="text" name="valeur_reference" value="{{ old('valeur_reference', $examen?->valeur_reference) }}" placeholder="0.70 - 1.10"
               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
    </div>
    <div>
        <label class="mb-1.5 block text-sm font-medium text-ink">Prix (FCFA)</label>
        <input type="number" name="prix" value="{{ old('prix', $examen?->prix) }}" min="0"
               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
    </div>
</div>
@if ($examen)
    <label class="flex items-center gap-2 text-sm text-inksoft">
        <input type="checkbox" name="actif" value="1" {{ old('actif', $examen->actif) ? 'checked' : '' }} class="h-4 w-4 rounded border-line text-primary focus:ring-primary/30">
        Examen actif (proposé lors des nouvelles demandes)
    </label>
@endif
