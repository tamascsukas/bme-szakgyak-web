<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use App\TrafficMeasurement;
use Carbon\Carbon;

/**
 * The controller which provide the API interface in connection with the traffic/battery measurements
 *
 * @category Class
 * @package  App
 * @author   Cs.T.
 *
 */
class MeasurementController extends Controller
{

    /**
     * Store measurement details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON String
     */
    public function processLoriotAPIRequest(Request $request) {
        // Security check
        if ($request->header('Authorization') != 'ge2am1Cl8acRom0zoPRenlh0papENic4XeTAhaHowraswad9lmoRek4jat2Iprub')
            return '';

        // Only rx packets
        if ($request->input('cmd') != 'rx')
            return '';

        // Get the device, or return
        $device = Device::findByEUI(substr(strtoupper($request->input('EUI')), 0, 16));
        if ($device === null)
            return '';

        // Update lastseen
        $device->last_seen = Carbon::now();
        $device->save();

        // Traffic data
        if ($request->input('port') == 1) {
            TrafficMeasurement::create([
                'device_id' => $device->id,
                'date' => Carbon::now(),
                'bicycle_count' => hexdec(substr($request->input('data'), 0, 2))
            ]);
        }

        // Logging
        $log = Carbon::now();
        $log .= ' | ';
        $log .= $request->getContent();
        $log .= "\n\r";
        file_put_contents ( './../storage/logs/loriot.log' , $log, FILE_APPEND);

        return '';
    }
}
