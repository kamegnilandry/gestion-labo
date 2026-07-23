<?php

namespace App\Http\Controllers;

use App\Models\DemandeAnalyse;
use App\Models\DemandeExamen;
use App\Models\Examen;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DemandeAnalyseController extends Controller
{
    public function index(Request $request): View
    {
        $statut = $request->get('statut');

        $demandes = DemandeAnalyse::with(['patient', 'lignes.examen'])
            ->when($statut, fn ($q) => $q->where('statut', $statut))
            ->latest('date_demande')
            ->paginate(10)
            ->withQueryString();

        $compteurs = collect(DemandeAnalyse::statuts())->keys()->mapWithKeys(
            fn ($s) => [$s => DemandeAnalyse::where('statut', $s)->count()]
        );

        return view('demandes.index', compact('demandes', 'statut', 'compteurs'));
    }

    public function create(Request $request): View
    {
        $patients = Patient::orderBy('nom')->get();
        $examens = Examen::actifs()->orderBy('categorie')->orderBy('nom')->get()->groupBy('categorie');
        $patientPreselectionne = $request->integer('patient_id');

        return view('demandes.create', compact('patients', 'examens', 'patientPreselectionne'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'examens' => ['required', 'array', 'min:1'],
            'examens.*' => ['exists:examens,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [], [
            'patient_id' => 'patient',
            'examens' => 'analyses demandées',
            'notes' => 'notes',
        ]);

        $demande = DemandeAnalyse::create([
            'patient_id' => $data['patient_id'],
            'created_by_id' => $request->user()->id,
            'statut' => DemandeAnalyse::STATUT_ENREGISTREE,
            'notes' => $data['notes'] ?? null,
        ]);

        foreach (array_unique($data['examens']) as $examenId) {
            DemandeExamen::create([
                'demande_analyse_id' => $demande->id,
                'examen_id' => $examenId,
            ]);
        }

        return redirect()->route('demandes.show', $demande)
            ->with('success', "Demande {$demande->reference} créée avec ".count($data['examens']).' analyse(s).');
    }

    public function show(DemandeAnalyse $demande): View
    {
        $demande->load(['patient', 'lignes.examen', 'lignes.resultat.saisiPar', 'prelevement.technicien', 'creePar', 'valideePar']);

        return view('demandes.show', compact('demande'));
    }

    public function valider(Request $request, DemandeAnalyse $demande): RedirectResponse
    {
        $demande->load('lignes.resultat');

        if (! $demande->toutesLignesOntResultat()) {
            return back()->with('error', "Impossible de valider : tous les résultats n'ont pas encore été saisis.");
        }

        $demande->update([
            'statut' => DemandeAnalyse::STATUT_VALIDEE,
            'validee_par_id' => $request->user()->id,
            'validee_at' => now(),
        ]);

        return back()->with('success', 'Résultats validés. Le compte-rendu peut maintenant être généré.');
    }

    public function annuler(Request $request, DemandeAnalyse $demande): RedirectResponse
    {
        if ($demande->statut === DemandeAnalyse::STATUT_VALIDEE) {
            return back()->with('error', 'Une demande déjà validée ne peut pas être annulée.');
        }

        $demande->update(['statut' => DemandeAnalyse::STATUT_ANNULEE]);

        return back()->with('success', "Demande {$demande->reference} annulée.");
    }
}
