<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReporteEvolucion extends Model
{
    use HasFactory;

    protected $table = 'reportes_evolucion';

    protected $fillable = [
        'cultivo_id',
        'observacion',
    ];

    public function cultivo(): BelongsTo
    {
        return $this->belongsTo(Cultivo::class, 'cultivo_id');
    }
}
