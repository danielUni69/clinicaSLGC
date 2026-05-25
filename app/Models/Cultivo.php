<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cultivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'servicio_id',
        'tipo_analisis_id',
        'estado_cultivo',
        'cepa_bacteriana',
        'bioquimico_id',
    ];

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function antibioticos(): BelongsToMany
    {
        return $this->belongsToMany(Antibiotico::class, 'antibiogramas', 'cultivo_id', 'antibiotico_id')
            ->using(Antibiograma::class) // <-- Le dice a Laravel que use tu modelo Pivot
            ->withPivot('susceptibilidad')
            ->withTimestamps();
    }

    public function tipoAnalisis(): BelongsTo
    {
        return $this->belongsTo(TipoAnalisis::class, 'tipo_analisis_id');
    }

    public function bioquimico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'bioquimico_id');
    }

    public function reportesEvolucion(): HasMany
    {
        return $this->hasMany(ReporteEvolucion::class, 'cultivo_id');
    }

    // Relación Muchos a Muchos con Antibióticos usando la tabla pivote antibiogramas
    public function antibioticos(): BelongsToMany
    {
        return $this->belongsToMany(Antibiotico::class, 'antibiogramas', 'cultivo_id', 'antibiotico_id')
            ->withPivot('susceptibilidad')
            ->withTimestamps();
    }
}
