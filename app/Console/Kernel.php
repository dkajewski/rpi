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
        'App\Console\Commands\ScreenBrightness',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //@todo move this code to commands maybe
        $schedule->call(function () {
            $weatherController = new WeatherController();
            $weather = ['data' => $weatherController->getCurrentWeatherArray(), 'event_type' => 'weather'];
            event(new HomeEvent($weather));
        })->everyThirtyMinutes();

        $schedule->call(function () {
            $notesController = new NotesController();
            $notes = ['data' => $notesController->getDisplayedNotes(), 'event_type' => 'notes'];
            event(new HomeEvent($notes));
        })->everyTenMinutes();

        $schedule->command('brightness:set')->hourly();
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
