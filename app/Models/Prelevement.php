<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prelevement extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_analyse_id',
        'type_echantillon',
        'technicien_id',
        'date_prelevement',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_prelevement' => 'datetime',
        ];
    }

    public function demande()
    {
        return $this->belongsTo(DemandeAnalyse::class, 'demande_analyse_id');
    }

    public function technicien()
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }
}
