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
        if (!$request->stopEvent) {
            event(new HomeEvent(['data' => $response]));
        }

        return response()->json(['data' => $response]);
    }
}