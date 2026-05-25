<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Antibiotico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_antibiotico',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function cultivos(): BelongsToMany
    {
        return $this->belongsToMany(Cultivo::class, 'antibiogramas', 'antibiotico_id', 'cultivo_id')
            ->withPivot('susceptibilidad')
            ->withTimestamps();
    }
}
