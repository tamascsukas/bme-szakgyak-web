<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatteryMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('battery_measurements', function (Blueprint $table) {
            //device_id, date, battery_level
            $table->increments('id');
            $table->unsignedInteger('device_id');
            $table->foreign('device_id')
                  ->references('id')->on('devices')
                  ->onDelete('cascade');
            $table->dateTime('date');
            $table->double('battery_level', 3, 2);
            $table->unique(['device_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('battery_measurements');
    }
}
