<?php

namespace App\Console;

use App\Events\HomeEvent;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\WeatherController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $weatherController = new WeatherController();
            $weatherController->getCurrentWeather(new \stdClass());
        })->hourly();

        $schedule->call(function () {
            $notesController = new NotesController();
            event(new HomeEvent(['data' => $notesController->getDisplayedNotes(), 'event_type' => 'notes']));
        })->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
