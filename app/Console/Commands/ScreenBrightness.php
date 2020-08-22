<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScreenBrightness extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brightness:set';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the brightness of RaspberryPi screen';

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
     *
     */
    public function handle()
    {
        $screenBrightnessFilepath = env('SCREEN_BRIGHTNESS_FILEPATH');
        $hour = (int)date('H');
        if (
            ($hour >= 0 && $hour <= 6)
            || ($hour >= 21 && $hour <= 23)
        ) {
            $brightness = 0;
        } else {
            $brightness = 50;
        }

        try {
            file_put_contents($screenBrightnessFilepath, $brightness);
        } catch (\Exception $e) {
            Log::channel('cron')->error($e->getMessage());
        }
    }
}
