<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ServicioAnalisis extends Pivot
{
    // Le indicamos que es una tabla pivote
    protected $table = 'servicio_analisis';

    // Si tuvieras campos extra en esta tabla pivote, se ponen aquí:
    // protected $fillable = ['servicio_id', 'tipo_analisis_id', 'precio_congelado'];
}
