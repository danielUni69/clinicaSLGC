<?php

namespace Database\Seeders;

use App\Models\Antibiotico;
use Illuminate\Database\Seeder;

class AntibioticoSeeder extends Seeder
{
    public function run(): void
    {
        $antibioticos = [
            'Amoxicilina', 'Amoxicilina + Ácido Clavulánico', 'Ampicilina',
            'Cefalexina', 'Cefotaxima', 'Ceftriaxona', 'Cefuroxima',
            'Ciprofloxacino', 'Levofloxacino', 'Azitromicina',
            'Gentamicina', 'Amikacina', 'Eritromicina', 'Clindamicina',
        ];

        foreach ($antibioticos as $anti) {
            Antibiotico::create([
                'nombre_antibiotico' => $anti,
                'estado' => true,
            ]);
        }
    }
}
