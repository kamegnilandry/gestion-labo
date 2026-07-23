<?php

namespace App\Http\Controllers;

use App\Models\DemandeAnalyse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RapportController extends Controller
{
    public function show(DemandeAnalyse $demande): StreamedResponse|RedirectResponse
    {
        if ($demande->statut !== DemandeAnalyse::STATUT_VALIDEE) {
            return back()->with('error', "Le compte-rendu n'est disponible qu'après validation des résultats.");
        }

        $demande->load(['patient', 'lignes.examen', 'lignes.resultat', 'valideePar', 'prelevement']);

        $pdf = Pdf::loadView('rapports.pdf', compact('demande'))->setPaper('a4');

        $nomFichier = 'compte-rendu-'.$demande->reference.'.pdf';

        return $pdf->stream($nomFichier);
    }
}
