<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TurnoDomingoFeriado extends Model
{
    use HasFactory;

    protected $table = 'turnos_domingo_feriados';

    protected $fillable = [
        'user_id',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function bioquimico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
