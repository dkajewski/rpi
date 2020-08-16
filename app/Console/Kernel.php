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
        //@todo move this code to commands maybe
        $schedule->call(function () {
            $weatherController = new WeatherController();
            $weatherController->getCurrentWeather(new \stdClass());
        })->hourly();

        $schedule->call(function () {
            $notesController = new NotesController();
            event(new HomeEvent(['data' => $notesController->getDisplayedNotes(), 'event_type' => 'notes']));
        })->everyTenMinutes();

        $schedule->call(function () {
            // @todo make this functionality depending on sunset
            $hour = (int)date('H');
            if (
                ($hour >= 0 && $hour <= 6)
                || ($hour >= 21 && $hour <= 23)
            ) {
                $brightness = 0;
                file_put_contents('/sys/class/backlight/rpi_backlight/brightness', $brightness);
            } else {
                $brightness = 60;
                file_put_contents('/sys/class/backlight/rpi_backlight/brightness', $brightness);
            }

            echo "\n".date('Y-m-d H:i:s')."\t"."Inserted brightness value: ".$brightness;
        })->hourly();
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