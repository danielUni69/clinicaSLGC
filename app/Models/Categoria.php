<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'es_cultivo'];

    public function tiposAnalisis()
    {
        return $this->hasMany(TipoAnalisis::class);
    }
}
