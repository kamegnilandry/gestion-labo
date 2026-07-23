<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultat extends Model
{
    use HasFactory;

    public const INTERPRETATION_NORMAL = 'normal';
    public const INTERPRETATION_ANORMAL = 'anormal';
    public const INTERPRETATION_CRITIQUE = 'critique';

    public static function interpretations(): array
    {
        return [
            self::INTERPRETATION_NORMAL => 'Normal',
            self::INTERPRETATION_ANORMAL => 'Anormal',
            self::INTERPRETATION_CRITIQUE => 'Critique',
        ];
    }

    protected $fillable = [
        'demande_examen_id',
        'valeur',
        'unite',
        'valeur_reference',
        'interpretation',
        'observations',
        'saisi_par_id',
    ];

    public function ligne()
    {
        return $this->belongsTo(DemandeExamen::class, 'demande_examen_id');
    }

    public function saisiPar()
    {
        return $this->belongsTo(User::class, 'saisi_par_id');
    }

    public function interpretationLabel(): string
    {
        return self::interpretations()[$this->interpretation] ?? '—';
    }
}
