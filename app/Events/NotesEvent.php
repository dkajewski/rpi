<?php

namespace App\Events;

class NotesEvent extends DefaultEvent
{

    public function broadcastAs()
    {
        return 'notes-event';
    }
}