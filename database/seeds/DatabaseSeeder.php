<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(DevicesTableSeeder::class);
        $this->call(BatteryMeasurementsTableSeeder::class);
        $this->call(TrafficMeasurementsTableSeeder::class);
    }
}
