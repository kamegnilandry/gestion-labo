<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeExamen extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_analyse_id',
        'examen_id',
    ];

    public function demande()
    {
        return $this->belongsTo(DemandeAnalyse::class, 'demande_analyse_id');
    }

    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    public function resultat()
    {
        return $this->hasOne(Resultat::class);
    }
}
