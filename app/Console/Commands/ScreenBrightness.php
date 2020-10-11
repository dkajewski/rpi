<?php

namespace App\Console\Commands;

use App\Configuration;
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
        $hour = (int)date('H');
        $screenBrightnessFilepath = env('SCREEN_BRIGHTNESS_FILEPATH');
        $brightnessConfig = Configuration::where([
            ['configuration_group', '=', 'screen_brightness'],
            ['configuration_key', '=', "SB_HOUR_$hour"],
        ])->first();

        try {
            file_put_contents($screenBrightnessFilepath, $brightnessConfig->configuration_value);
        } catch (\Exception $e) {
            Log::channel('cron')->error($e->getMessage());
        }
    }
}
