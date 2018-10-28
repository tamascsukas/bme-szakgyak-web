<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * The model object that represent a battery measurement; helps reaching data from DB.
 *
 * @category Class
 * @package  App
 * @author   Cs.T.
 *
 */
class BatteryMeasurement extends Model
{
	// Disable default timestamp properties
	public $timestamps = false;
    // Interpret these fields as Carbon date 
    protected $dates = [
        'date'
    ];
	// Specify editable properties
	protected $fillable = [
		'device_id',
		'date',
		'battery_level'
	];

    /**
     * Access the device that the specific measurement belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function device()
    {
        return $this->belongsTo('App\Device');
    }
}
