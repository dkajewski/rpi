<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RestartDevice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device:restart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the brightness of RaspberryPi screen';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        exec('reboot');
    }
}