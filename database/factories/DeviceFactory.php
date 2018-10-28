<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Device::class, function (Faker $faker) {
	//eui, name, lat, lon, last_seen, battery_level
	return [
		'eui' => $faker->regexify('[ABCDEF0-9]{16}'),
		'name' => $faker->text(30),
		'lat' => $faker->latitude(),
		'lon' => $faker->longitude(),
		'last_seen' => Carbon::createFromTimestamp($faker->numberBetween((Carbon::now()->timestamp - (48 * 60 * 60)), Carbon::now()->timestamp)),
		'battery_level' => $faker->numberBetween(0, 100)/100
	];
});