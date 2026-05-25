<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicoSolicitante extends Model
{
    use HasFactory;

    protected $table = 'medicos_solicitantes';

    protected $fillable = [
        'nombre_completo',
        'especialidad',
        'matricula_profesional',
        'correo',
    ];

    public function servicios(): HasMany
    {
        return $this->hasMany(Servicio::class, 'medico_id');
    }
}
