<?php

namespace App\Http\Controllers;

use App\Models\DemandeAnalyse;
use App\Models\Resultat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResultatController extends Controller
{
    public function edit(DemandeAnalyse $demande): View|RedirectResponse
    {
        if (! in_array($demande->statut, [DemandeAnalyse::STATUT_PRELEVEE, DemandeAnalyse::STATUT_RESULTATS_SAISIS], true)) {
            return redirect()->route('demandes.show', $demande)
                ->with('error', 'Le prélèvement doit être enregistré avant de saisir les résultats.');
        }

        $demande->load(['patient', 'lignes.examen', 'lignes.resultat']);

        return view('resultats.edit', compact('demande'));
    }

    public function update(Request $request, DemandeAnalyse $demande): RedirectResponse
    {
        $data = $request->validate([
            'resultats' => ['required', 'array'],
            'resultats.*.valeur' => ['required', 'string', 'max:100'],
            'resultats.*.unite' => ['nullable', 'string', 'max:30'],
            'resultats.*.interpretation' => ['nullable', 'in:normal,anormal,critique'],
            'resultats.*.observations' => ['nullable', 'string', 'max:500'],
        ], [], [
            'resultats.*.valeur' => 'valeur',
        ]);

        foreach ($data['resultats'] as $ligneId => $valeurs) {
            $ligne = $demande->lignes()->with('examen')->findOrFail($ligneId);

            Resultat::updateOrCreate(
                ['demande_examen_id' => $ligne->id],
                [
                    'valeur' => $valeurs['valeur'],
                    'unite' => $valeurs['unite'] ?? $ligne->examen->unite,
                    'valeur_reference' => $ligne->examen->valeur_reference,
                    'interpretation' => $valeurs['interpretation'] ?? null,
                    'observations' => $valeurs['observations'] ?? null,
                    'saisi_par_id' => $request->user()->id,
                ]
            );
        }

        $demande->update(['statut' => DemandeAnalyse::STATUT_RESULTATS_SAISIS]);

        return redirect()->route('demandes.show', $demande)
            ->with('success', 'Résultats enregistrés. En attente de validation par le responsable médical.');
    }
}
