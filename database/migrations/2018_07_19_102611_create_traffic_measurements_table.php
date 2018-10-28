<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrafficMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traffic_measurements', function (Blueprint $table) {
            //device_id, date, bicycle_count
            $table->increments('id');
            $table->unsignedInteger('device_id');
            $table->foreign('device_id')
                  ->references('id')->on('devices')
                  ->onDelete('cascade');
            $table->dateTime('date');
            $table->unsignedInteger('bicycle_count');
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
        Schema::dropIfExists('traffic_measurements');
    }
}
