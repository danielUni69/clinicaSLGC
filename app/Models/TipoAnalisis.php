<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TipoAnalisis extends Model
{
    use HasFactory;

    protected $table = 'tipos_analisis';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'costo',
        'unidad_medida',
        'estado',
    ];

    protected $casts = [
        'costo' => 'decimal:2',
        'estado' => 'boolean',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function servicios(): BelongsToMany
    {
        return $this->belongsToMany(Servicio::class, 'servicio_analisis', 'tipo_analisis_id', 'servicio_id');
    }
}
