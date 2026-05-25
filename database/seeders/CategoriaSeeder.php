<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        Categoria::create(['nombre' => 'Hematología']);
        Categoria::create(['nombre' => 'Química Sanguínea']);
        Categoria::create(['nombre' => 'Microbiología']);
    }
}
