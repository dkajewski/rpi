<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('weather', function (Blueprint $table) {
            $table->id();
            $table->string('weather', 50);
            $table->string('weather_description', 100);
            $table->double('temp', 5, 2);
            $table->double('feels_like', 5, 2);
            $table->integer('pressure');
            $table->double('humidity', 5, 2);
            $table->double('wind_speed', 5, 2);
            $table->smallInteger('cloudiness');
            $table->integer('sunrise');
            $table->integer('sunset');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weather');
    }
}
