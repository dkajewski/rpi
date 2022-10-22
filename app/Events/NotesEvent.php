<?php

namespace App\Events;

class NotesEvent extends DefaultEvent
{

    public function broadcastAs(): string
    {
        return 'notes-event';
    }
}