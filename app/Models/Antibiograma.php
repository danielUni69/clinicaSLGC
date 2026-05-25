<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Antibiograma extends Pivot
{
    protected $table = 'antibiogramas';

    // Aquí sí es importante el fillable por la susceptibilidad
    protected $fillable = [
        'cultivo_id',
        'antibiotico_id',
        'susceptibilidad',
    ];

    // Puedes agregar constantes para mantener tu código limpio en Livewire
    const SENSIBLE = 'S';

    const INTERMEDIO = 'I';

    const RESISTENTE = 'R';
}
