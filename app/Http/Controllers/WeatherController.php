<?php

namespace App\Http\Controllers;

use App\Events\HomeEvent;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WeatherController extends Controller
{

    /**
     * Function returns json object with current weather
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentWeather(Request $request)
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
        $data = [];
        if (!empty($response)) {
            $data['weather'] = $response->weather[0]->main;
            $data['weather_description'] = $response->weather[0]->description;
            $data['temp'] = $response->main->temp-273.15;
            $data['feels_like'] = $response->main->feels_like-273.15;
            $data['pressure'] = $response->main->pressure;
            $data['humidity'] = $response->main->humidity;
            $data['wind_speed'] = $response->wind->speed;
            $data['cloudiness'] = $response->clouds->all;
            $data['sunrise'] = $response->sys->sunrise;
            $data['sunset'] = $response->sys->sunset;
        }

        if (!($request->stopEvent ?? 0)) {
            event(new HomeEvent($data));
        }

        return response()->json(['data' => $data]);
    }
}