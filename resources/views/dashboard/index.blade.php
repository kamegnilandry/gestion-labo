@php $role = $user->role; @endphp

<x-layout title="Tableau de bord" eyebrow="Vue d'ensemble">
    <x-slot:headerActions>
        <span class="rounded-full bg-primary-soft px-3 py-1.5 text-xs font-medium text-primary shadow-sm">
            {{ now()->translatedFormat('l d F Y') }}
        </span>
    </x-slot:headerActions>

    <p class="mb-6 text-sm text-inksoft">
        Bonjour <strong>{{ $user->name }}</strong> —
        @switch($role)
            @case('admin')
                voici la vue d'ensemble du laboratoire.
                @break
            @case('receptionniste')
                bienvenue sur votre espace réception.
                @break
            @case('technicien')
                voici les tâches de prélèvement et d'analyse du jour.
                @break
            @case('biologiste')
                voici les demandes en attente de validation.
                @break
            @default
                voici l'activité du laboratoire.
        @endswitch
    </p>

    {{-- ========== STATS CARDS ========== --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <x-stat-card label="Patients enregistrés" :value="$stats['patients']" icon="users" tint="primary" />

        @switch($role)
            @case('admin')
                <x-stat-card label="Demandes totales" :value="$stats['demandes_total']" icon="flask-conical" tint="accent" />
                <x-stat-card label="En attente" :value="$stats['demandes_en_attente']" icon="hourglass" tint="warning" hint="À prélever, analyser ou valider" />
                <x-stat-card label="Validées" :value="$stats['demandes_validees']" icon="check-circle-2" tint="success" />
                @break

            @case('receptionniste')
                <x-stat-card label="Demandes totales" :value="$stats['demandes_total']" icon="flask-conical" tint="accent" />
                <x-stat-card label="En attente" :value="$stats['demandes_en_attente']" icon="hourglass" tint="warning" hint="À prendre en charge" />
                <x-stat-card label="Validées" :value="$stats['demandes_validees']" icon="check-circle-2" tint="success" />
                @break

            @case('technicien')
                <x-stat-card label="À prélever" :value="$stats['en_attente_prelevement']" icon="syringe" tint="warning" hint="Enregistrées, pas encore prélevées" />
                <x-stat-card label="Résultats à saisir" :value="$stats['en_attente_resultats']" icon="clipboard-edit" tint="violet" hint="Prélevées, résultats non saisis" />
                <x-stat-card label="Prélèvements aujourd'hui" :value="$stats['prelevements_aujourd_hui']" icon="calendar-clock" tint="primary" />
                @break

            @case('biologiste')
                <x-stat-card label="Demandes totales" :value="$stats['demandes_total']" icon="flask-conical" tint="accent" />
                <x-stat-card label="En attente de validation" :value="$stats['en_attente_validation']" icon="clipboard-check" tint="violet" hint="Résultats saisis, à valider" />
                <x-stat-card label="Validées aujourd'hui" :value="$stats['validees_aujourd_hui']" icon="check-circle-2" tint="success" />
                @break
        @endswitch
    </div>

    {{-- ========== ADMIN ========== --}}
    @if ($role === 'admin')
        <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div class="rounded-xl2 border border-line bg-surface p-5 shadow-card lg:col-span-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-display text-base font-semibold text-ink">Activité — 7 derniers jours</p>
                        <p class="text-xs text-inksoft/70">Nombre de demandes enregistrées par jour</p>
                    </div>
                </div>
                <div class="mt-4 h-64"><canvas id="graphiqueActivite"></canvas></div>
            </div>

            <div class="rounded-xl2 border border-line bg-surface p-5 shadow-card">
                <p class="font-display text-base font-semibold text-ink">Répartition par statut</p>
                <p class="text-xs text-inksoft/70">Toutes les demandes</p>
                <div class="mt-4 space-y-3">
                    @foreach (\App\Models\DemandeAnalyse::statuts() as $key => $label)
                        @php
                            $total = $repartitionStatuts[$key] ?? 0;
                            $max = max($repartitionStatuts->max() ?: 1, 1);
                            $pct = round(($total / $max) * 100);
                            $colors = ['enregistree' => 'bg-warning', 'prelevee' => 'bg-primary-light', 'resultats_saisis' => 'bg-violet', 'validee' => 'bg-success', 'annulee' => 'bg-danger'];
                        @endphp
                        <div>
                            <div class="mb-1 flex items-center justify-between text-xs">
                                <span class="text-inksoft">{{ $label }}</span>
                                <span class="font-mono font-medium text-ink">{{ $total }}</span>
                            </div>
                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-line/60">
                                <div class="h-full rounded-full {{ $colors[$key] ?? 'bg-primary' }}" style="width: {{ $total ? $pct : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-xl2 border border-line bg-surface p-5 shadow-card">
            <div class="flex items-center justify-between">
                <p class="font-display text-base font-semibold text-ink">Dernières demandes</p>
                <a href="{{ route('demandes.index') }}" class="text-xs font-semibold text-primary hover:underline">Voir tout →</a>
            </div>
            @if ($dernieresDemandes->isEmpty())
                <x-empty-state icon="flask-conical" title="Aucune demande pour le moment" description="Les nouvelles demandes d'analyses apparaîtront ici." class="mt-4" />
            @else
                <div class="mt-4 divide-y divide-line">
                    @foreach ($dernieresDemandes as $demande)
                        <a href="{{ route('demandes.show', $demande) }}" class="flex items-center gap-4 py-3.5 first:pt-0 last:pb-0 group">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-soft font-display text-sm font-semibold text-primary">
                                {{ mb_strtoupper(mb_substr($demande->patient->prenom, 0, 1).mb_substr($demande->patient->nom, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-ink group-hover:text-primary">{{ $demande->patient->nomComplet() }}</p>
                                <p class="font-mono text-xs text-inksoft/70">{{ $demande->reference }} · {{ $demande->date_demande->diffForHumans() }}</p>
                            </div>
                            <x-badge-statut :demande="$demande" class="hidden sm:inline-flex" />
                            <i data-lucide="chevron-right" class="h-4 w-4 text-inksoft/40 group-hover:text-primary"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    {{-- ========== RECEPTIONNISTE ========== --}}
    @if ($role === 'receptionniste')
        <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div class="rounded-xl2 border border-line bg-surface p-5 shadow-card lg:col-span-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-display text-base font-semibold text-ink">Activité — 7 derniers jours</p>
                        <p class="text-xs text-inksoft/70">Nombre de demandes enregistrées par jour</p>
                    </div>
                </div>
                <div class="mt-4 h-64"><canvas id="graphiqueActivite"></canvas></div>
            </div>

            <div class="rounded-xl2 border border-line bg-surface p-5 shadow-card">
                <p class="font-display text-base font-semibold text-ink">Patients enregistrés aujourd'hui</p>
                <p class="text-xs text-inksoft/70">Nouveaux patients du jour</p>
                <div class="mt-4 flex items-end gap-2">
                    <span class="font-display text-4xl font-bold text-primary">{{ $roleSpecific['patients_aujourd_hui'] ?? 0 }}</span>
                    <span class="pb-1 text-xs text-inksoft/60">patients</span>
                </div>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('patients.create') }}" class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-primary px-3.5 py-2.5 text-xs font-semibold text-white hover:bg-primary-dark transition">
                        <i data-lucide="plus" class="h-3.5 w-3.5"></i>
                        Nouveau patient
                    </a>
                    <a href="{{ route('demandes.create') }}" class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-accent px-3.5 py-2.5 text-xs font-semibold text-white hover:bg-accent-dark transition">
                        <i data-lucide="file-plus" class="h-3.5 w-3.5"></i>
                        Nouvelle demande
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- ========== TECHNICIEN ========== --}}
    @if ($role === 'technicien')
        <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div class="rounded-xl2 border border-line bg-surface p-5 shadow-card lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="font-display text-base font-semibold text-ink">Mes prélèvements récents</p>
                        <p class="text-xs text-inksoft/70">Vos 5 derniers prélèvements</p>
                    </div>
                </div>
                @if (empty($roleSpecific['mes_prelevements']) || $roleSpecific['mes_prelevements']->isEmpty())
                    <x-empty-state icon="syringe" title="Aucun prélèvement" description="Vos prélèvements enregistrés apparaîtront ici." />
                @else
                    <div class="divide-y divide-line">
                        @foreach ($roleSpecific['mes_prelevements'] as $prelevement)
                            <div class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-violet-soft font-display text-sm font-semibold text-violet">
                                    {{ mb_strtoupper(mb_substr($prelevement->demande->patient->prenom, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-ink">{{ $prelevement->demande->patient->nomComplet() }}</p>
                                    <p class="font-mono text-xs text-inksoft/60">{{ $prelevement->demande->reference }} · {{ $prelevement->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="shrink-0 rounded-full bg-violet-soft px-2.5 py-0.5 text-[11px] font-medium text-violet">
                                    {{ $prelevement->demande->statutLabel() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="mt-4">
                    <a href="{{ route('demandes.index') }}" class="text-xs font-semibold text-primary hover:underline">Voir toutes les demandes →</a>
                </div>
            </div>

            <div class="space-y-4">
                <div class="rounded-xl2 border border-line bg-surface p-5 shadow-card">
                    <p class="font-display text-base font-semibold text-ink">Actions rapides</p>
                    <div class="mt-3 space-y-2">
                        <a href="{{ route('demandes.index') }}?statut=enregistree" class="flex items-center gap-2.5 rounded-xl border border-line bg-white px-3.5 py-3 text-sm text-ink hover:bg-primary-soft hover:border-primary/30 transition">
                            <i data-lucide="syringe" class="h-4 w-4 text-warning"></i>
                            <span>Demandes à prélever</span>
                            @if (($stats['en_attente_prelevement'] ?? 0) > 0)
                                <span class="ml-auto flex h-5 min-w-[20px] items-center justify-center rounded-full bg-warning px-1.5 text-[11px] font-semibold text-white">{{ $stats['en_attente_prelevement'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('demandes.index') }}?statut=prelevee" class="flex items-center gap-2.5 rounded-xl border border-line bg-white px-3.5 py-3 text-sm text-ink hover:bg-violet-soft hover:border-violet/30 transition">
                            <i data-lucide="clipboard-edit" class="h-4 w-4 text-violet"></i>
                            <span>Résultats à saisir</span>
                            @if (($stats['en_attente_resultats'] ?? 0) > 0)
                                <span class="ml-auto flex h-5 min-w-[20px] items-center justify-center rounded-full bg-violet px-1.5 text-[11px] font-semibold text-white">{{ $stats['en_attente_resultats'] }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                <div class="rounded-xl2 border border-line bg-surface p-5 shadow-card">
                    <p class="font-display text-base font-semibold text-ink">Activité 7 jours</p>
                    <div class="mt-3"><canvas id="graphiquePetit" height="120"></canvas></div>
                </div>
            </div>
        </div>
    @endif

    {{-- ========== BIOLOGISTE ========== --}}
    @if ($role === 'biologiste')
        <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div class="rounded-xl2 border border-line bg-surface p-5 shadow-card lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="font-display text-base font-semibold text-ink">Demandes en attente de validation</p>
                        <p class="text-xs text-inksoft/70">Résultats saisis, en attente de votre validation</p>
                    </div>
                    <a href="{{ route('demandes.index') }}" class="text-xs font-semibold text-primary hover:underline">Voir tout →</a>
                </div>
                @if (empty($roleSpecific['a_valider']) || $roleSpecific['a_valider']->isEmpty())
                    <x-empty-state icon="clipboard-check" title="Tout est à jour !" description="Aucune demande en attente de validation." />
                @else
                    <div class="divide-y divide-line">
                        @foreach ($roleSpecific['a_valider'] as $demande)
                            <a href="{{ route('demandes.show', $demande) }}" class="flex items-center gap-4 py-3.5 first:pt-0 last:pb-0 group">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-violet-soft font-display text-sm font-semibold text-violet">
                                    {{ mb_strtoupper(mb_substr($demande->patient->prenom, 0, 1).mb_substr($demande->patient->nom, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-ink group-hover:text-violet">{{ $demande->patient->nomComplet() }}</p>
                                    <p class="font-mono text-xs text-inksoft/70">{{ $demande->reference }} · {{ $demande->date_demande->diffForHumans() }}</p>
                                </div>
                                <x-badge-statut :demande="$demande" class="hidden sm:inline-flex" />
                                <i data-lucide="chevron-right" class="h-4 w-4 text-inksoft/40 group-hover:text-violet"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-xl2 border border-line bg-surface p-5 shadow-card">
                <p class="font-display text-base font-semibold text-ink">Répartition par statut</p>
                <p class="text-xs text-inksoft/70">Vue synthétique</p>
                <div class="mt-4 space-y-3">
                    @foreach (\App\Models\DemandeAnalyse::statuts() as $key => $label)
                        @php
                            $total = $repartitionStatuts[$key] ?? 0;
                            $max = max($repartitionStatuts->max() ?: 1, 1);
                            $pct = round(($total / $max) * 100);
                            $colors = ['enregistree' => 'bg-warning', 'prelevee' => 'bg-primary-light', 'resultats_saisis' => 'bg-violet', 'validee' => 'bg-success', 'annulee' => 'bg-danger'];
                        @endphp
                        <div>
                            <div class="mb-1 flex items-center justify-between text-xs">
                                <span class="text-inksoft">{{ $label }}</span>
                                <span class="font-mono font-medium text-ink">{{ $total }}</span>
                            </div>
                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-line/60">
                                <div class="h-full rounded-full {{ $colors[$key] ?? 'bg-primary' }}" style="width: {{ $total ? $pct : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <x-slot:scripts>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
        <script>
            const activite = {!! json_encode($activite) !!};

            function creerGraphique(id, hauteur) {
                const canvas = document.getElementById(id);
                if (!canvas) return;
                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: activite.map(a => a.label),
                        datasets: [{
                            label: 'Demandes',
                            data: activite.map(a => a.total),
                            backgroundColor: '#0B3D3C',
                            borderRadius: 6,
                            maxBarThickness: id === 'graphiquePetit' ? 16 : 36,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0, color: '#4B5F5D' }, grid: { color: '#EDF2F1' } },
                            x: { ticks: { color: '#4B5F5D' }, grid: { display: false } }
                        }
                    }
                });
            }
            document.addEventListener('DOMContentLoaded', () => {
                creerGraphique('graphiqueActivite', 256);
                creerGraphique('graphiquePetit', 120);
            });
        </script>
    </x-slot:scripts>
</x-layout>

