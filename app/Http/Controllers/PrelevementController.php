<?php

namespace App\Http\Controllers;

use App\Models\DemandeAnalyse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrelevementController extends Controller
{
    private const TYPES_ECHANTILLON = [
        'Sang veineux',
        'Sang capillaire',
        'Urine',
        'Selles',
        'Écouvillon nasal/pharyngé',
        'Crachat',
        'Autre',
    ];

    public function create(DemandeAnalyse $demande): View|RedirectResponse
    {
        if ($demande->statut !== DemandeAnalyse::STATUT_ENREGISTREE) {
            return redirect()->route('demandes.show', $demande)
                ->with('error', 'Le prélèvement a déjà été enregistré pour cette demande.');
        }

        $demande->load(['patient', 'lignes.examen']);

        return view('prelevements.create', [
            'demande' => $demande,
            'types' => self::TYPES_ECHANTILLON,
        ]);
    }

    public function store(Request $request, DemandeAnalyse $demande): RedirectResponse
    {
        $data = $request->validate([
            'type_echantillon' => ['required', 'string', 'max:100'],
            'date_prelevement' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [], [
            'type_echantillon' => 'type d\'échantillon',
            'date_prelevement' => 'date de prélèvement',
        ]);

        $demande->prelevement()->create($data + ['technicien_id' => $request->user()->id]);
        $demande->update(['statut' => DemandeAnalyse::STATUT_PRELEVEE]);

        return redirect()->route('demandes.show', $demande)
            ->with('success', 'Prélèvement enregistré. La demande est prête pour la saisie des résultats.');
    }
}
