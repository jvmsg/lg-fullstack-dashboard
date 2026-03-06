<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductionMetricsSeeder extends Seeder
{
    public function run()
    {
        $productTypeIds = DB::table('product_types')->pluck('id')->toArray();
        $startDate = Carbon::createFromDate(2025, 12, 1);
        $endDate = Carbon::createFromDate(2026, 2, 28);

        $metrics = [];

        while ($startDate <= $endDate) {
            foreach ($productTypeIds as $productTypeId) {
                
              $quantityProduced = $this->generateQuantityProduced();
                
                  
              $quantityDefective = $this->generateQuantityDefective($quantityProduced);

              $metrics[] = [
                  'product_type_id' => $productTypeId,
                  'day' => $startDate->toDateString(),
                  'quantity_produced' => $quantityProduced,
                  'quantity_defective' => $quantityDefective,
                  'created_at' => now(),
                  'updated_at' => now(),
              ];
            }

            $startDate->addDay();
        }

        // Inserir em chunks para evitar problema de memória
        collect($metrics)->chunk(100)->each(function ($chunk) {
            DB::table('production_metrics')->insert($chunk->toArray());
        });
    }

    private function generateQuantityProduced()
    {
        return rand(500, 2000);
    }

    private function generateQuantityDefective($quantityProduced)
    {
        $maxDefective = intval($quantityProduced * 0.20);
        $quantityDefective = rand(0, max(1, $maxDefective));
        
        return $quantityDefective;
    }
}
