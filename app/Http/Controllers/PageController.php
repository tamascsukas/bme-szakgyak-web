<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;

/**
 * The controller which serves the site's pages.
 *
 * @category Class
 * @package  App
 * @author   Cs.T.
 *
 */
class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show a list of the devices.
     *
     * @return \Illuminate\Http\Response
     */
    public function listDevices() {
    	return view('devices.index');
    }

    // Seed DB
    public function seedTables() {
        Artisan::call('db:seed');
        echo Artisan::output();

        return '';
    }
}