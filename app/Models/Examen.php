<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'nom',
        'categorie',
        'unite',
        'valeur_reference',
        'prix',
        'actif',
    ];

    protected function casts(): array
    {
        return [
            'prix' => 'decimal:0',
            'actif' => 'boolean',
        ];
    }

    public function demandeExamens()
    {
        return $this->hasMany(DemandeExamen::class);
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }
}
