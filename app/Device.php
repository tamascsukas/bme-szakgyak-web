<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * The model object that represent a measuring device; helps reaching data from DB.
 *
 * @category Class
 * @package  App
 * @author   Cs.T.
 *
 */
class Device extends Model
{
	// Disable default timestamp properties
	public $timestamps = false;
    // Interpret these fields as Carbon date 
    protected $dates = [
        'last_seen'
    ];
	// Specify editable properties
	protected $fillable = [
		'eui',
		'name',
		'lat',
		'lon',
		'last_seen',
		'battery_level'
	];

    /**
     * Access traffic measurement entries belonging to the specific device
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trafficMeasurements()
    {
        return $this->hasMany('App\TrafficMeasurement');
    }

    /**
     * Access battery measurement entries belonging to the specific device
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function batteryMeasurements()
    {
        return $this->hasMany('App\BatteryMeasurement');
    }

    /**
     * Shortcut to get a device by it's EUI
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public static function findByEUI($eui) {
		return static::where('eui', $eui)->first();
	}

    /**
     * Get traffic statistics between dates
     *
     * @param  Date  $date_from The start date of the statistics
     * @param  Date  $date_to The end date of the statistics
     * @param  string  $result_date_format The date format string on which the aggregation will based
     * @return Array[date:String => bicycleCount:double]
     */
    public function getTrafficStats($date_from, $date_to, $result_date_format = 'm.d') {
        $result = [];
        $traffic = $this->trafficMeasurements()->whereBetween('date', array($date_from, $date_to))
                                                ->orderBy('date')
                                                ->get();
        $traffic->each(function ($item, $key) use (&$result, $result_date_format) {
            if (!isset($result[$item->date->format($result_date_format)]))
                $result[$item->date->format($result_date_format)] = 0;

            $result[$item->date->format($result_date_format)] += $item->bicycle_count;
        });

        return $result;
    }

    /**
     * Get last $days days of traffic statistics
     *
     * @param  int  $days Number of days should the statistics contains
     * @param  string  $date_format The date format string on which the aggregation will based
     * @return Array[date:String => bicycleCount:double]
     */
    public function getLastDaysTrafficStats($days = 1, $date_format = 'm.d') {
        $date_from = Carbon::now();
        $date_from->day = $date_from->day - $days;
        $date_to = Carbon::now();

        $result = $this->getTrafficStats($date_from, $date_to, $date_format);

        return $result;
    }

    /**
     * Get battery statistics between dates
     *
     * @param  Date  $date_from The start date of the statistics
     * @param  Date  $date_to The end date of the statistics
     * @param  string  $result_date_format The date format string on which the aggregation will based
     * @return Array[date:String => batteryLevel:double]
     */
    public function getBatteryStats($date_from, $date_to, $result_date_format = 'm.d') {
        $result = [];
        $battery = $this->batteryMeasurements()->whereBetween('date', array($date_from, $date_to))
                                                ->orderBy('id', 'DESC')
                                                ->get();
        $battery->each(function ($item, $key) use (&$result, $result_date_format) {
            if (!isset($result[$item->date->format($result_date_format)]))
                $result[$item->date->format($result_date_format)] = 0;

            $result[$item->date->format($result_date_format)] += $item->battery_level;
        });

        return $result;
    }

    /**
     * Get last $days days of battery statistics
     *
     * @param  int  $days Number of days should the statistics contains
     * @param  string  $date_format The date format string on which the aggregation will base
     * @return Array[date:String => batteryLevel:double]
     */
    public function getLastDaysBatteryStats($days = 1, $date_format = 'm.d') {
        $date_from = Carbon::now();
        $date_from->day = $date_from->day - $days;
        $date_to = Carbon::now();

        $result = $this->getBatteryStats($date_from, $date_to, $date_format);

        return $result;
    }
}
