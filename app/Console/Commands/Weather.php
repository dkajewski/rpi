<?php

namespace App\Console\Commands;

use App\Events\WeatherEvent;
use App\Http\Controllers\WeatherController;
use App\Repositories\WeatherRepository;
use App\Services\WeatherService;
use GuzzleHttp\Exception\GuzzleException;
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
     * @throws GuzzleException
     */
    public function handle()
    {
        $repository = new WeatherRepository(new \App\Weather());
        $weatherController = new WeatherController($repository, new WeatherService($repository));
        event(new WeatherEvent($weatherController->getCurrentWeatherFromApiResource()));
    }
}
