<?php

namespace App\Console\Commands;

use App\Events\NotesEvent;
use App\Http\Controllers\NotesController;
use Illuminate\Console\Command;

class Notes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notes:send-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends event to connected devices with upcoming notes';

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
        $notesController = new NotesController();
        event(new NotesEvent($notesController->getDisplayedNotes()));
    }
}
