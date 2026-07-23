<?php

namespace App\Http\Controllers;

use App\Models\DemandeAnalyse;
use App\Models\Patient;
use App\Models\Prelevement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $role = $user->role;

        // --- Statistiques socles ---
        $stats = [
            'patients' => Patient::count(),
            'demandes_total' => DemandeAnalyse::count(),
        ];

        $dernieresDemandes = DemandeAnalyse::with('patient')
            ->latest('date_demande')
            ->take(5)
            ->get();

        $activite = collect(range(6, 0))->map(function (int $offset) {
            $jour = now()->subDays($offset);
            return [
                'label' => $jour->translatedFormat('D d'),
                'total' => DemandeAnalyse::whereDate('date_demande', $jour)->count(),
            ];
        });

        $repartitionStatuts = DemandeAnalyse::selectRaw('statut, count(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        // --- Métriques par rôle ---
        $roleSpecific = [];

        switch ($role) {
            case User::ROLE_ADMIN:
                $stats['demandes_en_attente'] = DemandeAnalyse::whereIn('statut', [
                    DemandeAnalyse::STATUT_ENREGISTREE,
                    DemandeAnalyse::STATUT_PRELEVEE,
                    DemandeAnalyse::STATUT_RESULTATS_SAISIS,
                ])->count();
                $stats['demandes_validees'] = DemandeAnalyse::where('statut', DemandeAnalyse::STATUT_VALIDEE)->count();
                $roleSpecific['total_utilisateurs'] = User::count();
                break;

            case User::ROLE_RECEPTIONNISTE:
                $stats['demandes_en_attente'] = DemandeAnalyse::whereIn('statut', [
                    DemandeAnalyse::STATUT_ENREGISTREE,
                ])->count();
                $stats['demandes_validees'] = DemandeAnalyse::where('statut', DemandeAnalyse::STATUT_VALIDEE)->count();
                $roleSpecific['patients_aujourd_hui'] = Patient::whereDate('created_at', now())->count();
                $roleSpecific['mes_demandes'] = DemandeAnalyse::where('created_by_id', $user->id)
                    ->latest('date_demande')
                    ->take(5)
                    ->get();
                break;

            case User::ROLE_TECHNICIEN:
                $stats['prelevements_aujourd_hui'] = Prelevement::whereDate('created_at', now())->count();
                $stats['en_attente_prelevement'] = DemandeAnalyse::where('statut', DemandeAnalyse::STATUT_ENREGISTREE)->count();
                $stats['en_attente_resultats'] = DemandeAnalyse::where('statut', DemandeAnalyse::STATUT_PRELEVEE)->count();
                $roleSpecific['mes_prelevements'] = Prelevement::where('technicien_id', $user->id)
                    ->latest()
                    ->take(5)
                    ->get();
                break;

            case User::ROLE_BIOLOGISTE:
                $stats['en_attente_validation'] = DemandeAnalyse::where('statut', DemandeAnalyse::STATUT_RESULTATS_SAISIS)->count();
                $stats['validees_aujourd_hui'] = DemandeAnalyse::where('statut', DemandeAnalyse::STATUT_VALIDEE)
                    ->whereDate('validee_at', now())
                    ->count();
                $stats['demandes_validees'] = DemandeAnalyse::where('statut', DemandeAnalyse::STATUT_VALIDEE)->count();
                $roleSpecific['a_valider'] = DemandeAnalyse::with('patient')
                    ->where('statut', DemandeAnalyse::STATUT_RESULTATS_SAISIS)
                    ->latest('date_demande')
                    ->take(5)
                    ->get();
                break;
        }

        return view('dashboard.index', [
            'stats' => $stats,
            'activite' => $activite,
            'repartitionStatuts' => $repartitionStatuts,
            'dernieresDemandes' => $dernieresDemandes,
            'roleSpecific' => $roleSpecific,
            'user' => $user,
        ]);
    }
}
