<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Weather
 * @package App
 * @property $id
 * @property $weather
 * @property $weather_description
 * @property $temp
 * @property $feels_like
 * @property $pressure
 * @property $humidity
 * @property $wind_speed
 * @property $cloudiness
 * @property $sunrise
 * @property $sunset
 * @property $created_at
 * @property $updated_at
 */
class Weather extends Model
{
    protected $table = 'weather';

    protected $fillable = ['weather',
        'weather_description',
        'temp',
        'feels_like',
        'pressure',
        'humidity',
        'wind_speed',
        'cloudiness',
        'sunrise',
        'sunset'];
}
