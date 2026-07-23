<?php

namespace Database\Seeders;

use App\Models\DemandeAnalyse;
use App\Models\DemandeExamen;
use App\Models\Examen;
use App\Models\Patient;
use App\Models\Prelevement;
use App\Models\Resultat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Comptes utilisateurs de démonstration ---
        $admin = User::create([
            'name' => 'Admin Laboratoire',
            'email' => 'admin@labo.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'telephone' => '699000001',
        ]);

        $reception = User::create([
            'name' => 'Aïcha Ngo',
            'email' => 'reception@labo.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_RECEPTIONNISTE,
            'telephone' => '699000002',
        ]);

        $technicien = User::create([
            'name' => 'Paul Etoga',
            'email' => 'technicien@labo.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_TECHNICIEN,
            'telephone' => '699000003',
        ]);

        $biologiste = User::create([
            'name' => 'Dr. Sandrine Mballa',
            'email' => 'biologiste@labo.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_BIOLOGISTE,
            'telephone' => '699000004',
        ]);

        // --- Catalogue des examens ---
        $catalogue = [
            ['code' => 'NFS', 'nom' => 'Numération Formule Sanguine', 'categorie' => 'Hématologie', 'unite' => 'G/L', 'valeur_reference' => '4.0 - 10.0', 'prix' => 5000],
            ['code' => 'GLY', 'nom' => 'Glycémie à jeun', 'categorie' => 'Biochimie', 'unite' => 'g/L', 'valeur_reference' => '0.70 - 1.10', 'prix' => 3000],
            ['code' => 'CREA', 'nom' => 'Créatininémie', 'categorie' => 'Biochimie', 'unite' => 'mg/L', 'valeur_reference' => '6 - 13', 'prix' => 4000],
            ['code' => 'TP', 'nom' => 'Taux de Prothrombine', 'categorie' => 'Hémostase', 'unite' => '%', 'valeur_reference' => '70 - 100', 'prix' => 6000],
            ['code' => 'CRP', 'nom' => 'Protéine C-Réactive', 'categorie' => 'Immunologie', 'unite' => 'mg/L', 'valeur_reference' => '< 6', 'prix' => 7000],
            ['code' => 'TDR-PALU', 'nom' => 'Test de Diagnostic Rapide Paludisme', 'categorie' => 'Parasitologie', 'unite' => null, 'valeur_reference' => 'Négatif', 'prix' => 2000],
            ['code' => 'ECBU', 'nom' => 'Examen Cytobactériologique des Urines', 'categorie' => 'Bactériologie', 'unite' => null, 'valeur_reference' => 'Stérile', 'prix' => 8000],
            ['code' => 'HBA1C', 'nom' => 'Hémoglobine Glyquée', 'categorie' => 'Biochimie', 'unite' => '%', 'valeur_reference' => '4 - 6', 'prix' => 9000],
            ['code' => 'TSH', 'nom' => 'Thyréostimuline', 'categorie' => 'Endocrinologie', 'unite' => 'mUI/L', 'valeur_reference' => '0.4 - 4.0', 'prix' => 10000],
            ['code' => 'VIH', 'nom' => 'Sérologie VIH 1&2', 'categorie' => 'Sérologie', 'unite' => null, 'valeur_reference' => 'Négatif', 'prix' => 5000],
            ['code' => 'CHOL', 'nom' => 'Cholestérol total', 'categorie' => 'Biochimie', 'unite' => 'g/L', 'valeur_reference' => '< 2.00', 'prix' => 3500],
            ['code' => 'TRIG', 'nom' => 'Triglycérides', 'categorie' => 'Biochimie', 'unite' => 'g/L', 'valeur_reference' => '< 1.50', 'prix' => 3500],
        ];

        foreach ($catalogue as $examen) {
            Examen::create($examen);
        }

        // --- Patients et demandes de démonstration ---
        $patients = [
            ['nom' => 'Mbarga', 'prenom' => 'Jean', 'sexe' => 'M', 'date_naissance' => '1988-04-12', 'telephone' => '677010101', 'adresse' => 'Akwa, Douala'],
            ['nom' => 'Fotso', 'prenom' => 'Marie', 'sexe' => 'F', 'date_naissance' => '1995-09-23', 'telephone' => '677020202', 'adresse' => 'Bonapriso, Douala'],
            ['nom' => 'Njoya', 'prenom' => 'Ibrahim', 'sexe' => 'M', 'date_naissance' => '1972-01-05', 'telephone' => '677030303', 'adresse' => 'Bépanda, Douala'],
            ['nom' => 'Etame', 'prenom' => 'Solange', 'sexe' => 'F', 'date_naissance' => '2001-11-30', 'telephone' => '677040404', 'adresse' => 'Deido, Douala'],
        ];

        $examens = Examen::all();

        foreach ($patients as $i => $data) {
            $patient = Patient::create($data + ['created_by_id' => $reception->id]);

            $demande = DemandeAnalyse::create([
                'patient_id' => $patient->id,
                'created_by_id' => $reception->id,
                'statut' => DemandeAnalyse::STATUT_ENREGISTREE,
                'notes' => null,
            ]);

            $choisis = $examens->random(min(3, $examens->count()));
            foreach ($choisis as $examen) {
                DemandeExamen::create([
                    'demande_analyse_id' => $demande->id,
                    'examen_id' => $examen->id,
                ]);
            }

            // On fait avancer la première demande jusqu'à la validation, pour la démo du tableau de bord.
            if ($i === 0) {
                $prelevement = Prelevement::create([
                    'demande_analyse_id' => $demande->id,
                    'type_echantillon' => 'Sang veineux',
                    'technicien_id' => $technicien->id,
                    'date_prelevement' => now()->subHours(3),
                ]);
                $demande->update(['statut' => DemandeAnalyse::STATUT_PRELEVEE]);

                foreach ($demande->lignes as $ligne) {
                    Resultat::create([
                        'demande_examen_id' => $ligne->id,
                        'valeur' => (string) random_int(1, 10),
                        'unite' => $ligne->examen->unite,
                        'valeur_reference' => $ligne->examen->valeur_reference,
                        'interpretation' => Resultat::INTERPRETATION_NORMAL,
                        'saisi_par_id' => $technicien->id,
                    ]);
                }
                $demande->update([
                    'statut' => DemandeAnalyse::STATUT_VALIDEE,
                    'validee_par_id' => $biologiste->id,
                    'validee_at' => now()->subHour(),
                ]);
            } elseif ($i === 1) {
                Prelevement::create([
                    'demande_analyse_id' => $demande->id,
                    'type_echantillon' => 'Urine',
                    'technicien_id' => $technicien->id,
                    'date_prelevement' => now()->subHour(),
                ]);
                $demande->update(['statut' => DemandeAnalyse::STATUT_PRELEVEE]);
            }
        }
    }
}
