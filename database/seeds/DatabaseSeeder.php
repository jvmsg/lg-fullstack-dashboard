<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Preciso executar productTypes antes de productionMetrics porque productionMetrics depende de productTypes
        $this->call(ProductTypesSeeder::class);
        $this->call(ProductionMetricsSeeder::class);
        // $this->call(UsersTableSeeder::class);
    }
}
