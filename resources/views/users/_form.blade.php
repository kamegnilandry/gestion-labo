@php $user = $user ?? null; @endphp
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-1.5 block text-sm font-medium text-ink">Nom complet</label>
        <input type="text" name="name" value="{{ old('name', $user?->name) }}" required
               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
    </div>
    <div>
        <label class="mb-1.5 block text-sm font-medium text-ink">Adresse e-mail</label>
        <input type="email" name="email" value="{{ old('email', $user?->email) }}" required
               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
    </div>
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-1.5 block text-sm font-medium text-ink">Rôle</label>
        <select name="role" required
                class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
            @foreach ($roles as $val => $label)
                <option value="{{ $val }}" {{ old('role', $user?->role) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1.5 block text-sm font-medium text-ink">Téléphone <span class="text-inksoft/50">(optionnel)</span></label>
        <input type="text" name="telephone" value="{{ old('telephone', $user?->telephone) }}"
               class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
    </div>
</div>

<div>
    <label class="mb-1.5 block text-sm font-medium text-ink">
        Mot de passe
        @if ($user) <span class="text-inksoft/50">(laisser vide pour ne pas modifier)</span> @endif
    </label>
    <input type="password" name="password" placeholder="{{ $user ? '••••••••' : 'Minimum 8 caractères' }}" {{ $user ? '' : 'required' }}
           class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
</div>

@if ($user)
    <label class="flex items-center gap-2 text-sm text-inksoft">
        <input type="checkbox" name="actif" value="1" {{ old('actif', $user->actif) ? 'checked' : '' }} class="h-4 w-4 rounded border-line text-primary focus:ring-primary/30">
        Compte actif (l'utilisateur peut se connecter)
    </label>
@endif
