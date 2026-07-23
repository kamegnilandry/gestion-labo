<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $recherche = trim((string) $request->get('q'));

        $patients = Patient::query()
            ->when($recherche !== '', function ($query) use ($recherche) {
                $query->where(function ($q) use ($recherche) {
                    $q->where('nom', 'like', "%{$recherche}%")
                        ->orWhere('prenom', 'like', "%{$recherche}%")
                        ->orWhere('code_patient', 'like', "%{$recherche}%")
                        ->orWhere('telephone', 'like', "%{$recherche}%");
                });
            })
            ->withCount('demandes')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('patients.index', compact('patients', 'recherche'));
    }

    public function create(): View
    {
        return view('patients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['created_by_id'] = $request->user()->id;

        $patient = Patient::create($data);

        return redirect()->route('patients.show', $patient)
            ->with('success', "Patient {$patient->nomComplet()} enregistré avec succès (dossier {$patient->code_patient}).");
    }

    public function show(Patient $patient): View
    {
        $patient->load(['demandes.lignes.examen', 'demandes.lignes.resultat']);

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient): View
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $patient->update($this->validateData($request, $patient));

        return redirect()->route('patients.show', $patient)->with('success', 'Dossier patient mis à jour.');
    }

    private function validateData(Request $request, ?Patient $patient = null): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'sexe' => ['required', 'in:M,F'],
            'date_naissance' => ['required', 'date', 'before:today'],
            'telephone' => ['required', 'string', 'max:30'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
        ], [], [
            'nom' => 'nom',
            'prenom' => 'prénom',
            'sexe' => 'sexe',
            'date_naissance' => 'date de naissance',
            'telephone' => 'téléphone',
            'adresse' => 'adresse',
            'email' => 'adresse électronique',
        ]);
    }
}
