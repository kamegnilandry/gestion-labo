<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Tableau de bord' }} — LaboSuite</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bg: '#F6F8F7',
                        surface: '#FFFFFF',
                        ink: '#0E2321',
                        inksoft: '#4B5F5D',
                        primary: { DEFAULT: '#0B3D3C', light: '#12615F', dark: '#082A29', soft: '#E7F0EE' },
                        accent: { DEFAULT: '#D65A3D', soft: '#FBE6E0', dark: '#B94B31' },
                        success: { DEFAULT: '#1F8A5F', soft: '#DFF3E8' },
                        warning: { DEFAULT: '#C98A2C', soft: '#FBEEDA' },
                        danger: { DEFAULT: '#C1443A', soft: '#FBE4E1' },
                        violet: { DEFAULT: '#6E5AA8', soft: '#EBE7F6' },
                        line: '#DCE4E2',
                    },
                    fontFamily: {
                        display: ['Fraunces', 'ui-serif', 'serif'],
                        sans: ['Inter', 'ui-sans-serif', 'sans-serif'],
                        mono: ['"IBM Plex Mono"', 'ui-monospace', 'monospace'],
                    },
                    boxShadow: {
                        card: '0 1px 2px rgba(14,35,33,0.04), 0 8px 24px -12px rgba(14,35,33,0.10)',
                        pop: '0 12px 32px -8px rgba(14,35,33,0.22)',
                        sidebar: '4px 0 24px -8px rgba(14,35,33,0.18)',
                    },
                    borderRadius: { xl2: '1.25rem' },
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js" defer></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body { font-feature-settings: "ss01" 1; }
        .font-display { letter-spacing: -0.01em; }
        [x-cloak] { display: none !important; }
        ::selection { background: #12615F; color: #fff; }
        .scrollbar-thin::-webkit-scrollbar { width: 6px; height: 6px; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #DCE4E2; border-radius: 999px; }

        /* === Sidebar mobile slide === */
        .mobile-nav-panel {
            transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .mobile-nav-overlay {
            transition: opacity 0.3s ease-out;
        }

        /* === Hamburger animation === */
        .hamburger-btn {
            position: relative;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: background 0.2s ease;
        }
        .hamburger-btn:hover {
            background: rgba(75, 95, 93, 0.08);
        }
        .hamburger-btn:active {
            background: rgba(75, 95, 93, 0.14);
            transform: scale(0.94);
        }
        .hamburger-inner {
            position: relative;
            width: 20px;
            height: 14px;
        }
        .hamburger-line {
            position: absolute;
            left: 0;
            width: 100%;
            height: 2px;
            background: #4B5F5D;
            border-radius: 2px;
            transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .hamburger-line:nth-child(1) { top: 0; }
        .hamburger-line:nth-child(2) { top: 6px; }
        .hamburger-line:nth-child(3) { top: 12px; }

        .hamburger-btn.is-open .hamburger-line:nth-child(1) {
            top: 6px;
            transform: rotate(45deg);
            background: #0E2321;
        }
        .hamburger-btn.is-open .hamburger-line:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }
        .hamburger-btn.is-open .hamburger-line:nth-child(3) {
            top: 6px;
            transform: rotate(-45deg);
            background: #0E2321;
        }

        /* === Nav item micro-interaction === */
        .nav-item {
            position: relative;
            overflow: hidden;
        }
        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%) scaleY(0);
            width: 3px;
            height: 60%;
            background: #D65A3D;
            border-radius: 0 3px 3px 0;
            transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .nav-item:hover::before {
            transform: translateY(-50%) scaleY(0.6);
        }
        .nav-item.is-active::before {
            transform: translateY(-50%) scaleY(1);
        }
        .nav-item.is-active {
            background: rgba(255, 255, 255, 1);
            color: #0B3D3C;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(14,35,33,0.08);
        }
        .nav-item:not(.is-active):hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        /* === Role badge === */
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        /* === Mobile header actions === */
        @media (max-width: 639px) {
            .header-actions-mobile {
                display: flex !important;
            }
        }

        /* === Sidebar mobile polish === */
        @media (max-width: 1023px) {
            .mobile-nav-panel {
                box-shadow: 4px 0 32px -8px rgba(14,35,33,0.25);
                border-radius: 0 16px 16px 0;
            }
        }
    </style>
    {{ $head ?? '' }}
</head>
<body class="h-full bg-bg text-ink font-sans antialiased">
<div x-data="{ mobileNav: false }"
     @keydown.window.escape="mobileNav = false; document.body.classList.remove('overflow-hidden')"
     x-effect="document.body.classList.toggle('overflow-hidden', mobileNav)"
     class="min-h-full lg:flex">

    <!-- ============ SIDEBAR ============ -->
    <aside :class="mobileNav ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="mobile-nav-panel fixed inset-y-0 left-0 z-40 w-72 shrink-0 bg-primary lg:static lg:translate-x-0 flex flex-col">

        <!-- Close button — mobile only -->
        <button @click="mobileNav = false; document.body.classList.remove('overflow-hidden')"
                class="absolute right-3 top-3 z-50 flex h-8 w-8 items-center justify-center rounded-xl bg-white/10 text-white/60 hover:bg-white/20 hover:text-white transition lg:hidden"
                aria-label="Fermer le menu">
            <i data-lucide="x" class="h-4 w-4"></i>
        </button>

        <!-- Brand + Role badge -->
        <div class="flex items-center gap-3 px-6 pt-6 pb-4">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl2 bg-white/10 ring-1 ring-white/15">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v5.5L4.4 17a2 2 0 001.75 3h11.7a2 2 0 001.75-3L15 8.5V3M9 3h6M9 12h6"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="font-display text-lg font-semibold text-white leading-none truncate">LaboSuite</p>
                <p class="text-[10px] uppercase tracking-[0.14em] text-white/40 mt-1">Gestion de laboratoire</p>
            </div>
        </div>

        @auth
        @php
            $role = auth()->user()->role;
            $roleColors = [
                'admin' => ['bg' => 'bg-accent', 'text' => 'text-white'],
                'receptionniste' => ['bg' => 'bg-blue-500', 'text' => 'text-white'],
                'technicien' => ['bg' => 'bg-violet', 'text' => 'text-white'],
                'biologiste' => ['bg' => 'bg-emerald-500', 'text' => 'text-white'],
            ];
            $rc = $roleColors[$role] ?? ['bg' => 'bg-white/20', 'text' => 'text-white/80'];
        @endphp
        <div class="px-6 pb-3">
            <span class="role-badge {{ $rc['bg'] }} {{ $rc['text'] }}">
                <i data-lucide="circle" class="h-1.5 w-1.5 fill-current"></i>
                {{ auth()->user()->roleLabel() }}
            </span>
        </div>
        @endauth

        <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 pb-4 scrollbar-thin">
            @auth
                @php
                    $role = auth()->user()->role;
                    $navItems = [];

                    // === ADMIN : tout voir ===
                    if ($role === 'admin') {
                        $navItems = [
                            ['route' => 'dashboard', 'label' => 'Tableau de bord', 'icon' => 'layout-dashboard'],
                            ['route' => 'patients.index', 'label' => 'Patients', 'icon' => 'users'],
                            ['route' => 'demandes.index', 'label' => 'Demandes d\'analyses', 'icon' => 'flask-conical'],
                            ['route' => 'examens.index', 'label' => 'Catalogue des examens', 'icon' => 'list-checks'],
                        ];
                    }

                    // === RÉCEPTIONNISTE : patients, demandes, examens ===
                    if ($role === 'receptionniste') {
                        $navItems = [
                            ['route' => 'dashboard', 'label' => 'Tableau de bord', 'icon' => 'layout-dashboard'],
                            ['route' => 'patients.index', 'label' => 'Patients', 'icon' => 'users'],
                            ['route' => 'demandes.index', 'label' => 'Demandes d\'analyses', 'icon' => 'flask-conical'],
                            ['route' => 'examens.index', 'label' => 'Catalogue des examens', 'icon' => 'list-checks'],
                        ];
                    }

                    // === TECHNICIEN : prélèvements, résultats, examens ===
                    if ($role === 'technicien') {
                        $navItems = [
                            ['route' => 'dashboard', 'label' => 'Tableau de bord', 'icon' => 'layout-dashboard'],
                            ['route' => 'demandes.index', 'label' => 'Demandes en cours', 'icon' => 'flask-conical'],
                            ['route' => 'examens.index', 'label' => 'Catalogue des examens', 'icon' => 'list-checks'],
                        ];
                    }

                    // === BIOLOGISTE : validations, demandes, rapports ===
                    if ($role === 'biologiste') {
                        $navItems = [
                            ['route' => 'dashboard', 'label' => 'Tableau de bord', 'icon' => 'layout-dashboard'],
                            ['route' => 'demandes.index', 'label' => 'Demandes à valider', 'icon' => 'clipboard-check'],
                            ['route' => 'examens.index', 'label' => 'Catalogue des examens', 'icon' => 'list-checks'],
                        ];
                    }
                @endphp

                @foreach ($navItems as $item)
                    @php $active = request()->routeIs($item['route'].'*'); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="nav-item group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition {{ $active ? 'is-active' : '' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="h-[18px] w-[18px] shrink-0 {{ $active ? 'text-accent' : 'text-white/50 group-hover:text-white/80' }}"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach

                {{-- Section admin : utilisateurs --}}
                @if ($role === 'admin')
                    <p class="px-3.5 pt-5 pb-1.5 text-[11px] font-semibold uppercase tracking-[0.14em] text-white/35">Administration</p>
                    <a href="{{ route('utilisateurs.index') }}"
                       class="nav-item group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm transition {{ request()->routeIs('utilisateurs.*') ? 'is-active' : '' }}">
                        <i data-lucide="shield-check" class="h-[18px] w-[18px] shrink-0 {{ request()->routeIs('utilisateurs.*') ? 'text-accent' : 'text-white/50 group-hover:text-white/80' }}"></i>
                        <span>Utilisateurs &amp; rôles</span>
                    </a>
                @endif
            @endauth
        </nav>

        @auth
        <div class="border-t border-white/10 p-4">
            <div class="flex items-center gap-3 rounded-xl bg-white/5 px-3 py-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-accent/90 font-display text-sm font-semibold text-white shadow-sm">
                    {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                    <p class="truncate text-[11px] text-white/50">{{ auth()->user()->roleLabel() }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Se déconnecter" class="rounded-lg p-1.5 text-white/50 hover:bg-white/10 hover:text-white transition">
                        <i data-lucide="log-out" class="h-4 w-4"></i>
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </aside>

    <!-- Overlay avec flou -->
    <div @click="mobileNav = false; document.body.classList.remove('overflow-hidden')"
         x-show="mobileNav"
         x-cloak
         x-transition.opacity.duration.300ms
         class="mobile-nav-overlay fixed inset-0 z-30 bg-ink/50 backdrop-blur-sm lg:hidden"></div>

    <!-- ============ MAIN ============ -->
    <div class="flex min-h-full flex-1 flex-col">
        <header class="sticky top-0 z-20 flex items-center gap-3 border-b border-line bg-bg/85 px-4 py-3 backdrop-blur lg:px-8 lg:py-4">

            <!-- Hamburger artisanal -->
            <button @click="mobileNav = !mobileNav"
                    :class="{ 'is-open': mobileNav }"
                    class="hamburger-btn shrink-0 lg:hidden"
                    aria-label="Menu de navigation">
                <div class="hamburger-inner">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </div>
            </button>

            <div class="min-w-0 flex-1">
                <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-inksoft/60">{{ $eyebrow ?? 'LaboSuite' }}</p>
                <h1 class="truncate font-display text-xl font-semibold text-ink sm:text-2xl">{{ $title ?? 'Tableau de bord' }}</h1>
            </div>

            <!-- Header actions : visibles sur mobile aussi -->
            <div class="flex shrink-0 items-center gap-2 header-actions-mobile">
                {{ $headerActions ?? '' }}
            </div>
        </header>

        <main class="flex-1 px-4 py-5 sm:px-6 lg:px-8 lg:py-8">
            @if (session('success'))
                <div class="mb-5 flex items-start gap-3 rounded-xl2 border border-success/20 bg-success-soft px-4 py-3.5 text-sm text-success shadow-sm" role="alert">
                    <i data-lucide="check-circle-2" class="mt-0.5 h-4 w-4 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-5 flex items-start gap-3 rounded-xl2 border border-danger/20 bg-danger-soft px-4 py-3.5 text-sm text-danger shadow-sm" role="alert">
                    <i data-lucide="alert-triangle" class="mt-0.5 h-4 w-4 shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-5 flex items-start gap-3 rounded-xl2 border border-danger/20 bg-danger-soft px-4 py-3.5 text-sm text-danger shadow-sm" role="alert">
                    <i data-lucide="alert-triangle" class="mt-0.5 h-4 w-4 shrink-0"></i>
                    <ul class="list-inside list-disc space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => window.lucide && lucide.createIcons());
    document.addEventListener('alpine:init', () => window.lucide && lucide.createIcons());
    window.addEventListener('load', () => window.lucide && lucide.createIcons());
</script>
{{ $scripts ?? '' }}
</body>
</html>

