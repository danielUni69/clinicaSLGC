<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'medico_id',
        'codigo_unico',
        'estado_pago',
        'estado_muestra',
        'observaciones_calidad',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(MedicoSolicitante::class, 'medico_id');
    }

    public function tiposAnalisis(): BelongsToMany
    {
        return $this->belongsToMany(TipoAnalisis::class, 'servicio_analisis', 'servicio_id', 'tipo_analisis_id');
    }

    public function recibo(): HasOne
    {
        return $this->hasOne(Recibo::class, 'servicio_id');
    }

    public function resultados(): HasMany
    {
        return $this->hasMany(ResultadoAnalisis::class, 'servicio_id');
    }

    public function cultivos(): HasMany
    {
        return $this->hasMany(Cultivo::class, 'servicio_id');
    }
}
