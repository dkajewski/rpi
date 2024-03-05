<?php

namespace App\Http\Controllers;

use App\Interfaces\Repository\WeatherRepositoryInterface;
use App\Services\WeatherService;
use App\Traits\SendJsonResponse;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

class WeatherController extends Controller
{
    use SendJsonResponse;

    private WeatherRepositoryInterface $repository;

    private WeatherService $service;

    public function __construct(WeatherRepositoryInterface $repository, WeatherService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Function returns json object with current weather
     * When last weather update is older than 1 hour - there is an API call for newest
     *
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function getCurrentWeather(): JsonResponse
    {
        return $this->sendJsonResponse($this->service->getCurrentWeather());
    }

    public function getCurrentWeatherFromApiResource(): JsonResponse
    {
        return $this->sendJsonResponse($this->service->getCurrentWeatherFromApiResource());
    }
}
