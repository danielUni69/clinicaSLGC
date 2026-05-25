<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <-- ¡Corregido aquí!
use Illuminate\Database\Eloquent\Relations\HasMany;

class Responsable extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'celular',
        'relacion',
        'correo',
    ];

    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class, 'responsable_id');
    }
}
