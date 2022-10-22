<?php

namespace App\Events;

class WeatherEvent extends DefaultEvent
{

    public function broadcastAs(): string
    {
        return 'weather-event';
    }
}