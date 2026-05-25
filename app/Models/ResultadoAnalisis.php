<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultadoAnalisis extends Model
{
    use HasFactory;

    protected $table = 'resultados_analisis';

    protected $fillable = [
        'servicio_id',
        'tipo_analisis_id',
        'valor_registrado',
        'alerta_rango',
        'bioquimico_id',
    ];

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function tipoAnalisis(): BelongsTo
    {
        return $this->belongsTo(TipoAnalisis::class, 'tipo_analisis_id');
    }

    public function bioquimico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'bioquimico_id');
    }
}
