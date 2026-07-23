<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_patient',
        'nom',
        'prenom',
        'sexe',
        'date_naissance',
        'telephone',
        'adresse',
        'email',
        'created_by_id',
    ];

    protected function casts(): array
    {
        return [
            'date_naissance' => 'date',
        ];
    }

    public function nomComplet(): string
    {
        return trim($this->prenom.' '.mb_strtoupper($this->nom));
    }

    public function age(): ?int
    {
        return $this->date_naissance?->age;
    }

    public function creePar()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function demandes()
    {
        return $this->hasMany(DemandeAnalyse::class)->latest('date_demande');
    }

    protected static function booted(): void
    {
        static::creating(function (Patient $patient) {
            if (empty($patient->code_patient)) {
                $last = static::orderByDesc('id')->first();
                $next = $last ? ((int) substr($last->code_patient, -6)) + 1 : 1;
                $patient->code_patient = 'PAT-'.str_pad((string) $next, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
