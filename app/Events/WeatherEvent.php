<?php

namespace App\Events;

class WeatherEvent extends DefaultEvent
{

    public function broadcastAs()
    {
        return 'weather-event';
    }
}