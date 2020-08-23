<?php

namespace App\Console\Commands;

use App\Events\HomeEvent;
use App\Http\Controllers\WeatherController;
use Illuminate\Console\Command;

class Weather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:send-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches weather information from API and sends event to all connected devices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $weatherController = new WeatherController();
        $weather = ['data' => $weatherController->getCurrentWeatherArray(), 'event_type' => 'weather'];
        event(new HomeEvent($weather));
    }
}
