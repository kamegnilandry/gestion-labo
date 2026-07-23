<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamenController extends Controller
{
    public function index(): View
    {
        $examens = Examen::orderBy('categorie')->orderBy('nom')->get()->groupBy('categorie');

        return view('examens.index', compact('examens'));
    }

    public function create(): View
    {
        return view('examens.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Examen::create($this->validateData($request));

        return redirect()->route('examens.index')->with('success', "L'examen a été ajouté au catalogue.");
    }

    public function edit(Examen $examen): View
    {
        return view('examens.edit', compact('examen'));
    }

    public function update(Request $request, Examen $examen): RedirectResponse
    {
        $examen->update($this->validateData($request, $examen));

        return redirect()->route('examens.index')->with('success', "L'examen a été mis à jour.");
    }

    public function destroy(Examen $examen): RedirectResponse
    {
        if ($examen->demandeExamens()->exists()) {
            $examen->update(['actif' => false]);

            return redirect()->route('examens.index')
                ->with('success', "Cet examen est déjà utilisé dans des demandes : il a été désactivé plutôt que supprimé.");
        }

        $examen->delete();

        return redirect()->route('examens.index')->with('success', "L'examen a été supprimé du catalogue.");
    }

    private function validateData(Request $request, ?Examen $examen = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:20', 'alpha_dash', 'unique:examens,code,'.($examen?->id)],
            'nom' => ['required', 'string', 'max:150'],
            'categorie' => ['nullable', 'string', 'max:100'],
            'unite' => ['nullable', 'string', 'max:30'],
            'valeur_reference' => ['nullable', 'string', 'max:100'],
            'prix' => ['nullable', 'integer', 'min:0'],
            'actif' => ['sometimes', 'boolean'],
        ], [], [
            'code' => 'code',
            'nom' => 'nom de l\'examen',
            'categorie' => 'catégorie',
            'unite' => 'unité',
            'valeur_reference' => 'valeur de référence',
            'prix' => 'prix',
        ]) + ['actif' => $request->boolean('actif', true)];
    }
}
