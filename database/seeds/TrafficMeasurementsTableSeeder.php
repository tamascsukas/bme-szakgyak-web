<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TrafficMeasurementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 2400 measurements per each device
        $devices = App\Device::all();
        $devices->each(function ($item, $key) {
        	for ($i = 0; $i < 2400; $i++) {
                // One by hour, at '00
        		$date = Carbon::now();
        		$date->second = 0;
        		$date->minute = 0;
        		$date->hour = $date->hour - $i;

                if (9 <= $date->hour && $date->hour <= 17 && 0 < $date->dayOfWeek && $date->dayOfWeek < 6 && rand(0, 1) == 1) {
    	        	App\TrafficMeasurement::create([
    	        		'device_id' => $item['id'],
    	        		'date' => $date,
    	        		'bicycle_count' => rand(0, 20)
    	        	]);
                }
            }
        });
    }
}
