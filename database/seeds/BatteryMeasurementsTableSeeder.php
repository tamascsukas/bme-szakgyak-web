<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BatteryMeasurementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 100 measurements per each device
        $devices = App\Device::all();
        $devices->each(function ($item, $key) {
            $b_level = rand(90, 100)/100;
            
        	for ($i = 0; $i < 100; $i++) {
                // One by day, at 00:00
        		$date = Carbon::now();
        		$date->second = 0;
        		$date->minute = 0;
        		$date->hour = 0;
        		$date->day = $date->day - $i;

                if (0 < $date->dayOfWeek && $date->dayOfWeek < 6 && rand(0, 1) == 1) {
    	        	App\BatteryMeasurement::create([
    	        		'device_id' => $item['id'],
    	        		'date' => $date,
    	        		'battery_level' => $b_level
    	        	]);

                    $b_level = $b_level - rand(4, 14)/100/100;
                }
            }
        });
    }
}
