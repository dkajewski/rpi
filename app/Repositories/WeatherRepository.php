<?php

namespace App\Repositories;

use App\Interfaces\Repository\WeatherRepositoryInterface;
use App\Weather;

class WeatherRepository implements WeatherRepositoryInterface
{

    private Weather $model;

    public function __construct(Weather $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        $this->model::create($data);
    }

    public function getLatestEntry()
    {
        return $this->model::latest()->first();
    }
}
