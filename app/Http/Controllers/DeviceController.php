<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Device;
use App\Http\Controllers\Paginator;

/**
 * The controller which provide the API interface in connection with the measuring devices
 *
 * @category Class
 * @package  App
 * @author   Cs.T.
 *
 */
class DeviceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.api');
    }

    /**
     * Display a list of the devices.
     *
     * @return JSON string
     */
    public function index(Request $request)
    {
        $devices = null;
        if ($request->has('map')) {
            $devices = Device::all()->map(function ($item, $key) {
                return (object)[
                    'id' => $item->id,
                    'name' => $item->name,
                    'lat' => $item->lat,
                    'lon' => $item->lon
                ];
            });
        } else {
            $devices = Device::all();

            // filter by GEO bounds coordinates
            if ($request->has('bounds')) {
                $bounds = $request->input('bounds');
                $devices = $devices->filter(function ($value, $key) use ($bounds) {
                    return GEO_inBounds($value->lat, $value->lon, $bounds['ne']['lat'], $bounds['ne']['lon'], $bounds['sw']['lat'], $bounds['sw']['lon']);
                });
            }

            $devices->transform(function ($item) {
                $item->last_seen_hours = null;
                if ($item->last_seen)
                    $item->last_seen_hours = floor((Carbon::now()->timestamp - $item->last_seen->timestamp) / 60 / 60);
                return $item;
            });

            $devices = COLLECTION_paginate($devices, 10);
        }

        return json_encode($devices);
    }

    /**
     * Store a newly created device in storage or edit an oldone.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON String
     */
    public function store(Request $request)
    {
        $device = $request->isMethod('put') ? Device::find((int)$request->input('id')) : new Device;

        if ($device === null) {
            return json_encode(['result' => 'item_not_found']);
        }

        $device->eui = substr(strtoupper($request->input('eui')), 0, 16);
        $device->name = substr($request->input('name'), 0, 30);
        $device->lat = ($request->input('lat') == '') ? null : (double)$request->input('lat');
        $device->lon = ($request->input('lon') == '') ? null : (double)$request->input('lon');

        // input validation
        $errors = $this->validateFields($device);
        if (count($errors) > 0) {
            return json_encode(['result' => 'action_failed', 'errors' => $errors]);
        }

        // saving the instance
        if (!$device->save()) {
            return json_encode(['result' => 'action_failed']);
        }

        return json_encode(['result' => 'success', 'data' => $device]);
    }

    /**
     * Display the specified device.
     *
     * @param  int  $id
     * @return JSON String
     */
    public function show($id)
    {
        $device = Device::find((int)$id);

        if ($device === null) {
            return json_encode(['result' => 'item_not_found']);
        }

        // last_seen_hours
        $device->last_seen_hours = null;
        if ($device->last_seen)
            $device->last_seen_hours = floor((Carbon::now()->timestamp - $device->last_seen->timestamp) / 60 / 60);

        // battery stat
        $device->battery = (object)['labels' => [], 'data' => []];
        $battery_arr = $device->getLastDaysBatteryStats(20, 'm.d');
        foreach($battery_arr as $key => $val) {
            $device->battery->labels[] = $key;
            $device->battery->data[] = (int)($val*100);
        };

        // last weeks stat
        $device->threeweeksstat = (object)['labels' => [], 'data' => []];
        $traffic_arr = $device->getLastDaysTrafficStats(3 * 7, 'm.d');
        foreach ($traffic_arr as $key => $val) {
            $device->threeweeksstat->labels[] = $key;
            $device->threeweeksstat->data[] = $val;
        };

        return json_encode(['result' => 'success', 'data' => $device]);
    }

    /**
     * Remove the specified device from storage.
     *
     * @param  int  $id
     * @return JSON String
     */
    public function destroy($id)
    {
        $device = Device::find((int)$id);

        if ($device === null) {
            return json_encode(['result' => 'item_not_found']);
        } else if (!$device->delete()) {
            return json_encode(['result' => 'action_failed']);
        }

        return json_encode(['result' => 'success']);
    }

    /**
     * Display traffic statistics belongs to the specified device
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return JSON String
     */
    public function showTrafficStatistics($id, Request $request)
    {
        $device = Device::find((int)$id);

        if ($device === null) {
            return json_encode(['result' => 'item_not_found']);
        }

        $date = substr(strtolower($request->input('date')), 0, 24);
        $date = explode(' to ', $date);
        if ($date[0] == '' || $date[1] == '') {
            return json_encode(['result' => 'date_not_specified']);
        }

        $date_from = Carbon::createFromFormat('Y-m-d H:i:s', $date[0] . '00:00:00');
        $date_to = Carbon::createFromFormat('Y-m-d H:i:s', $date[1] . '23:59:59');

        $date_format = 'm.d';
        switch ($request->input('step')) {
            case 'hours':
                $date_format = 'm.d H\h';
                break;
            case 'days':
                $date_format = 'm.d';
                break;
            case 'months':
                $date_format = 'Y.m';
                break;
        }

        $result = (object)['labels' => [], 'data' => []];
        $traffic_arr = $device->getTrafficStats($date_from, $date_to, $date_format);
        foreach ($traffic_arr as $key => $val) {
            $result->labels[] = $key;
            $result->data[] = $val;
        };

        return json_encode(['result' => 'success', 'data' => $result]);
    }

    /**
     * Validate the properties of a device instance.
     *
     * @param  App\Device $device
     * @return Array of the errors
     */
    private function validateFields(Device $device) {
        $errors = [];

        // Name
        if ($device->name == '') {
            $errors[] = 'name_is_blank';
        }

        // EUI
        if ($device->eui == '') {
            $errors[] = 'eui_is_blank';
        } else if (($deviceByEUI = Device::findByEUI($device->eui)) !== null && $deviceByEUI->id != $device->id) {
            $errors[] = 'eui_already_added';
        }

        // latitude
        if ($device->lat !== null && ($device->lat < -90 || 90 < $device->lat)) {
            $errors[] = 'invalid_lat_value';
        }

        // longitude
        if ($device->lon !== null && ($device->lon < -180 || 180 < $device->lon)) {
            $errors[] = 'invalid_lon_value';
        }

        return $errors;
    }
}
