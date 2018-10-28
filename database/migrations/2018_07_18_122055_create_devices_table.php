<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            //id, eui, name, lat, lon, last_seen, battery_level
            $table->increments('id');
            $table->string('eui');
                $table->unique('eui');
            $table->string('name');
            $table->float('lat', 9, 6)->nullable();
            $table->float('lon', 9, 6)->nullable();
            $table->dateTime('last_seen')->nullable();
            $table->double('battery_level', 3, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
