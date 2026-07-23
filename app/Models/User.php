<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_RECEPTIONNISTE = 'receptionniste';
    public const ROLE_TECHNICIEN = 'technicien';
    public const ROLE_BIOLOGISTE = 'biologiste';

    public static function roles(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrateur',
            self::ROLE_RECEPTIONNISTE => 'Réceptionniste',
            self::ROLE_TECHNICIEN => 'Technicien de laboratoire',
            self::ROLE_BIOLOGISTE => 'Responsable médical / Biologiste',
        ];
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telephone',
        'actif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }

    public function roleLabel(): string
    {
        return self::roles()[$this->role] ?? $this->role;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        return in_array($this->role, $roles, true);
    }

    public function demandesCreees()
    {
        return $this->hasMany(DemandeAnalyse::class, 'created_by_id');
    }

    public function prelevementsRealises()
    {
        return $this->hasMany(Prelevement::class, 'technicien_id');
    }
}
