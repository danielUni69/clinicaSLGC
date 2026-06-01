<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        Categoria::updateOrCreate(
            ['nombre' => 'Hematología'],
            []
        );
        Categoria::updateOrCreate(
            ['nombre' => 'Química Sanguínea'],
            []
        );
        Categoria::updateOrCreate(
            ['nombre' => 'Microbiología'],
            []
        );

    }
}
