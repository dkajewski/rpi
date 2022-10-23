<?php

namespace App\Interfaces\Repository;

interface WeatherRepositoryInterface
{
    public function create(array $data);
    public function getLatestEntry();
}
