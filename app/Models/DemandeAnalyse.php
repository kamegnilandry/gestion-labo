<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeAnalyse extends Model
{
    use HasFactory;

    public const STATUT_ENREGISTREE = 'enregistree';
    public const STATUT_PRELEVEE = 'prelevee';
    public const STATUT_RESULTATS_SAISIS = 'resultats_saisis';
    public const STATUT_VALIDEE = 'validee';
    public const STATUT_ANNULEE = 'annulee';

    public static function statuts(): array
    {
        return [
            self::STATUT_ENREGISTREE => 'Enregistrée',
            self::STATUT_PRELEVEE => 'Prélevée',
            self::STATUT_RESULTATS_SAISIS => 'Résultats saisis',
            self::STATUT_VALIDEE => 'Validée',
            self::STATUT_ANNULEE => 'Annulée',
        ];
    }

    protected $fillable = [
        'reference',
        'patient_id',
        'created_by_id',
        'statut',
        'date_demande',
        'notes',
        'validee_par_id',
        'validee_at',
    ];

    protected function casts(): array
    {
        return [
            'date_demande' => 'datetime',
            'validee_at' => 'datetime',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function creePar()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function valideePar()
    {
        return $this->belongsTo(User::class, 'validee_par_id');
    }

    public function lignes()
    {
        return $this->hasMany(DemandeExamen::class);
    }

    public function prelevement()
    {
        return $this->hasOne(Prelevement::class);
    }

    public function statutLabel(): string
    {
        return self::statuts()[$this->statut] ?? $this->statut;
    }

    public function statutCouleur(): string
    {
        return match ($this->statut) {
            self::STATUT_ENREGISTREE => 'ambre',
            self::STATUT_PRELEVEE => 'bleu',
            self::STATUT_RESULTATS_SAISIS => 'violet',
            self::STATUT_VALIDEE => 'vert',
            self::STATUT_ANNULEE => 'rouge',
            default => 'gris',
        };
    }

    /** Étape 1-4 pour le composant "tube de progression" (voir signature visuelle). */
    public function etape(): int
    {
        return match ($this->statut) {
            self::STATUT_ENREGISTREE => 1,
            self::STATUT_PRELEVEE => 2,
            self::STATUT_RESULTATS_SAISIS => 3,
            self::STATUT_VALIDEE => 4,
            default => 0,
        };
    }

    public function toutesLignesOntResultat(): bool
    {
        return $this->lignes->every(fn (DemandeExamen $l) => $l->resultat !== null);
    }

    protected static function booted(): void
    {
        static::creating(function (DemandeAnalyse $demande) {
            if (empty($demande->reference)) {
                $jour = now()->format('Ymd');
                $count = static::whereDate('created_at', now())->count() + 1;
                $demande->reference = 'DEM-'.$jour.'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
            }
            if (empty($demande->date_demande)) {
                $demande->date_demande = now();
            }
        });
    }
}
