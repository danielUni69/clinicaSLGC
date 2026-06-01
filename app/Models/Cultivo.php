<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
    protected $fillable = [
        'servicio_id',
        'tipo_analisis_id',
        'estado_cultivo',
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

    public function antibiogramas()
    {
        return $this->hasMany(Antibiograma::class);
    }

    public function reportesEvolucion()
    {
        return $this->hasMany(ReporteEvolucion::class, 'cultivo_id');
    }
}