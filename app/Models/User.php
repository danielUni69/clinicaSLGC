<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    // Relaciones
    public function turnos(): HasMany
    {
        return $this->hasMany(TurnoDomingoFeriado::class, 'user_id');
    }

    public function resultadosProcesados(): HasMany
    {
        return $this->hasMany(ResultadoAnalisis::class, 'bioquimico_id');
    }

    public function cultivosAsignados(): HasMany
    {
        return $this->hasMany(Cultivo::class, 'bioquimico_id');
    }
}
