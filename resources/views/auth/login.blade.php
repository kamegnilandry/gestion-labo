<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — LaboSuite</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: {
                bg: '#F6F8F7', surface: '#FFFFFF', ink: '#0E2321', inksoft: '#4B5F5D',
                primary: { DEFAULT: '#0B3D3C', light: '#12615F', dark: '#082A29', soft: '#E7F0EE' },
                accent: { DEFAULT: '#D65A3D', soft: '#FBE6E0', dark: '#B94B31' },
                danger: { DEFAULT: '#C1443A', soft: '#FBE4E1' },
                line: '#DCE4E2',
            },
            fontFamily: {
                display: ['Fraunces', 'ui-serif', 'serif'],
                sans: ['Inter', 'ui-sans-serif', 'sans-serif'],
                mono: ['"IBM Plex Mono"', 'ui-monospace', 'monospace'],
            },
            borderRadius: { xl2: '1.25rem' },
        } } }
    </script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js" defer></script>
</head>
<body class="h-full bg-bg font-sans text-ink antialiased">
<div class="grid min-h-full lg:grid-cols-2">

    <!-- Left : brand panel -->
    <div class="relative hidden overflow-hidden bg-primary lg:flex lg:flex-col lg:justify-between lg:p-12">
        <div class="absolute inset-0 opacity-[0.07]"
             style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 28px 28px;"></div>

        <div class="relative flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl2 bg-white/10 ring-1 ring-white/15">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v5.5L4.4 17a2 2 0 001.75 3h11.7a2 2 0 001.75-3L15 8.5V3M9 3h6M9 12h6"/>
                </svg>
            </div>
            <span class="font-display text-lg font-semibold text-white">LaboSuite</span>
        </div>

        <div class="relative max-w-md">
            <p class="font-display text-[2.6rem] font-medium leading-[1.12] text-white">
                Chaque échantillon,<br>suivi à la goutte près.
            </p>
            <p class="mt-5 text-[15px] leading-relaxed text-white/60">
                De l'enregistrement du patient à la validation du compte-rendu, LaboSuite trace chaque étape
                de la demande d'analyse en temps réel.
            </p>

            <!-- Signature: tube-track illustration -->
            <div class="mt-10 rounded-xl2 border border-white/10 bg-white/5 p-5 font-mono text-[11px] text-white/50">
                <div class="mb-3 flex items-center justify-between text-white/70">
                    <span>DEM-{{ now()->format('Ymd') }}-0001</span>
                    <span class="text-white/40">MBARGA Jean</span>
                </div>
                <div class="flex items-center">
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="flex items-center {{ $i < 4 ? 'flex-1' : '' }}">
                            <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full {{ $i <= 3 ? 'bg-accent' : 'bg-white/15' }}">
                                @if ($i <= 3)
                                    <svg class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </div>
                            @if ($i < 4)
                                <div class="mx-1 h-[2px] flex-1 rounded-full {{ $i < 3 ? 'bg-accent' : 'bg-white/15' }}"></div>
                            @endif
                        </div>
                    @endfor
                </div>
                <div class="mt-2 grid grid-cols-4 uppercase tracking-wide text-white/40">
                    <span>Enreg.</span><span class="text-center">Prélevée</span><span class="text-center">Résultats</span><span class="text-right">Validée</span>
                </div>
            </div>
        </div>

        <p class="relative text-xs text-white/35">© {{ now()->year }} LaboSuite — Logiciel de gestion de laboratoire médical.</p>
    </div>

    <!-- Right : form -->
    <div class="flex items-center justify-center px-6 py-12 sm:px-10">
        <div class="w-full max-w-sm">
            <div class="mb-8 flex items-center gap-3 lg:hidden">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl2 bg-primary ring-1 ring-primary/15">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v5.5L4.4 17a2 2 0 001.75 3h11.7a2 2 0 001.75-3L15 8.5V3M9 3h6M9 12h6"/>
                    </svg>
                </div>
                <span class="font-display text-lg font-semibold text-ink">LaboSuite</span>
            </div>

            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-accent">Accès sécurisé</p>
            <h1 class="mt-2 font-display text-3xl font-semibold text-ink">Connexion</h1>
            <p class="mt-2 text-sm text-inksoft">Entrez vos identifiants pour accéder à votre espace.</p>

            @if ($errors->any())
                <div class="mt-6 flex items-start gap-2.5 rounded-xl2 border border-danger/20 bg-danger-soft px-4 py-3 text-sm text-danger">
                    <i data-lucide="alert-triangle" class="mt-0.5 h-4 w-4 shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
                @csrf
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-ink">Adresse e-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="vous@labo.test"
                           class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm text-ink placeholder:text-inksoft/40 focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                </div>
                <div>
                    <div class="mb-1.5 flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium text-ink">Mot de passe</label>
                    </div>
                    <input id="password" type="password" name="password" required
                           placeholder="••••••••"
                           class="w-full rounded-xl border border-line bg-white px-3.5 py-2.5 text-sm text-ink placeholder:text-inksoft/40 focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10">
                </div>
                <label class="flex items-center gap-2 text-sm text-inksoft">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-line text-primary focus:ring-primary/30">
                    Rester connecté
                </label>

                <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3 text-sm font-semibold text-white shadow-card transition hover:bg-primary-dark active:scale-[0.99]">
                    Se connecter
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </button>
            </form>

            <div class="mt-8 rounded-xl2 border border-line bg-white/60 p-4">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-inksoft/60">Comptes de démonstration</p>
                <div class="mt-2.5 space-y-1.5 font-mono text-[12px] text-inksoft">
                    <p>admin@labo.test</p>
                    <p>reception@labo.test</p>
                    <p>technicien@labo.test</p>
                    <p>biologiste@labo.test</p>
                    <p class="pt-1 text-ink">Mot de passe pour tous : <span class="font-semibold">password</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => window.lucide && lucide.createIcons());</script>
</body>
</html>
