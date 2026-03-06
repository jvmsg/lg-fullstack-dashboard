<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypesSeeder extends Seeder
{
    public function run()
    {
        $productTypes = [
            'Geladeira',
            'Máquina de Lavar',
            'TV',
            'Ar-Condicionado',
        ];

        foreach ($productTypes as $type) {
            DB::table('product_types')->insert([
                'name' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
