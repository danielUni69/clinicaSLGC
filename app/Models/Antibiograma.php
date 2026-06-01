<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antibiograma extends Model
{
    protected $fillable = ['cultivo_id', 'antibiotico_id', 'susceptibilidad'];

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class);
    }

    public function antibiotico()
    {
        return $this->belongsTo(Antibiotico::class);
    }
}
