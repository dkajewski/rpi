<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Weather
 * @package App\Http\Resources
 */
class Weather extends JsonResource
{
    /**
     * Transform the resource into an array
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'weather' => $this->weather,
            'weather_description' => $this->weather_description,
            'temp' => $this->temp,
            'feels_like' => $this->feels_like,
            'pressure' => $this->pressure,
            'humidity' => $this->humidity,
            'wind_speed' => $this->wind_speed,
            'cloudiness' => $this->cloudiness,
            'sunrise' => $this->sunrise,
            'sunset' => $this->sunset,
            'updated_at' => date('d.m H:i', strtotime($this->updated_at)),
        ];
    }
}