<?php

namespace App\Http\Controllers;

use App\Weather;
use GuzzleHttp\Client;
use App\Http\Resources\Weather as WeatherResource;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{

    /**
     * Function returns json object with current weather
     * When last weather update is older than 1 hour - there is an API call for newest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentWeather()
    {
        $weather = $this->getCurrentWeatherFromDb();
        $lastUpdate = strtotime((string) $weather->updated_at);
        $now = time();
        if ($now - $lastUpdate > 3600) {
            try {
                $weather = $this->getCurrentWeatherFromApi();
            } catch (\Exception $e) {
                Log::channel()->error($e->getMessage());
            }

        }

        return response()->json(['data' => $weather]);
    }

    /**
     * Retrieves current weather from api
     * @return WeatherResource
     */
    public function getCurrentWeatherFromApi()
    {
        $apiKey = env('OPENWEATHERMAP_API_KEY');
        $latitude = env('OWM_LATITUDE');
        $longitude = env('OWM_LONGITUDE');
        $client = new Client();
        $params = [
            'query' => [
                'lat' => $latitude,
                'lon' => $longitude,
                'appid' => $apiKey
            ]
        ];

        $response = json_decode($client->get('https://api.openweathermap.org/data/2.5/weather/', $params)->getBody()->getContents());
        $weather = new Weather();
        if (!empty($response)) {
            $weather->weather = $response->weather[0]->main;
            $weather->weather_description = $response->weather[0]->description;
            $weather->temp = $response->main->temp-273.15;
            $weather->feels_like = $response->main->feels_like-273.15;
            $weather->pressure = $response->main->pressure;
            $weather->humidity = $response->main->humidity;
            $weather->wind_speed = $response->wind->speed;
            $weather->cloudiness = $response->clouds->all;
            $weather->sunrise = $response->sys->sunrise;
            $weather->sunset = $response->sys->sunset;
        }

        $weather->save();

        return new WeatherResource($weather);
    }

    /**
     * Retrieves current weather from database
     * @return WeatherResource
     */
    private function getCurrentWeatherFromDb()
    {
        return new WeatherResource(Weather::latest()->first());
    }
}