<?php

namespace App\Repositories;

use App\Interfaces\Repository\WeatherRepositoryInterface;
use App\Weather;

class WeatherRepository extends Repository implements WeatherRepositoryInterface
{

    public function __construct(Weather $model)
    {
        $this->model = $model;
    }

    public function getLatestEntry()
    {
        return $this->model::latest()->first();
    }
}
