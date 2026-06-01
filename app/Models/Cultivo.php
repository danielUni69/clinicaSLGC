<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'servicio_id',
        'tipo_analisis_id',
        'estado_cultivo', // 'en_incubacion', 'negativo', 'positivo_identificado'
        'cepa_bacteriana',
        'bioquimico_id',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function tipoAnalisis()
    {
        return $this->belongsTo(TipoAnalisis::class);
    }

    public function bioquimico()
    {
        return $this->belongsTo(User::class, 'bioquimico_id');
    }

    // Relaciones hijas (Nivel 5)
    public function evoluciones()
    {
        return $this->hasMany(ReporteEvolucion::class);
    }

    public function antibiogramas()
    {
        return $this->hasMany(Antibiograma::class);
    }
}
