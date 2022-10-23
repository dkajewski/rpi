<?php

namespace App\Services;

use App\Repositories\WeatherRepository;
use App\Http\Resources\Weather as WeatherResource;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class WeatherService
{

    private WeatherRepository $repository;

    public function __construct(WeatherRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function getCurrentWeatherFromApi(): mixed
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
        if (!empty($response)) {
            $weather = [
                'weather' => $response->weather[0]->main,
                'weather_description' => $response->weather[0]->description,
                'temp' => $response->main->temp-273.15,
                'feels_like' => $response->main->feels_like-273.15,
                'pressure' => $response->main->pressure,
                'humidity' => $response->main->humidity,
                'wind_speed' => $response->wind->speed,
                'cloudiness' => $response->clouds->all,
                'sunrise' => $response->sys->sunrise,
                'sunset' => $response->sys->sunset,
            ];

            $this->repository->create($weather);
        }

        return $this->repository->getLatestEntry();
    }

    /**
     * @return WeatherResource
     * @throws GuzzleException
     */
    public function getCurrentWeather(): WeatherResource
    {
        $weather = $this->repository->getLatestEntry();
        if (empty($weather->updated_at)) {
            $weather = $this->getCurrentWeatherFromApi();
        }

        $lastUpdate = strtotime((string) $weather->updated_at);
        $now = time();
        if ($now - $lastUpdate > 3600) {
            try {
                $weather = $this->getCurrentWeatherFromApi();
            } catch (\Exception $e) {
                Log::channel()->error($e->getMessage());
            }
        }

        return new WeatherResource($weather);
    }
}
