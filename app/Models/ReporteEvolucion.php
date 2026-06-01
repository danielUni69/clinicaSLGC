<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteEvolucion extends Model
{
    protected $table = 'reportes_evolucion';

    protected $fillable = [
        'cultivo_id',
        'observacion',
    ];

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class);
    }
}