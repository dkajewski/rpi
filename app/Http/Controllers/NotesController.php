<?php

namespace App\Http\Controllers;

use App\Events\HomeEvent;
use App\Note;
use App\Http\Resources\Note as NoteResource;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function index()
    {
        $resource = $this->getDisplayedNotes();

        return response()->json(['data' => $resource]);
    }

    public function store(Request $request)
    {
        $note = new Note();
        $note->description = $request->description ?? '';
        $note->start_at = date('Y-m-d', strtotime($request->start_at)) ?? '';
        $note->end_at = date('Y-m-d', strtotime($request->end_at)) ?? '';
        if ($note->save()) {
            event(new HomeEvent(['data' => $this->getDisplayedNotes(), 'event_type' => 'notes']));
            event(new HomeEvent(['data' => $this->getAllFutureNotesArray(), 'event_type' => 'futureNotes']));
            return response()->json(['type' => 'success', 'message' => __('basic.saved-successfully').': '.$note->description]);
        }

        return response()->json(['type' => 'error', 'message' => __('basic.error-occurred')]);
    }

    public function getAllFutureNotes()
    {
        $resource = $this->getAllFutureNotesArray();

        return response()->json(['data' => $resource]);
    }

    private function getAllFutureNotesArray()
    {
        $notes = Note::where('start_at', '>=', date('Y-m-d'))
            ->orderBy('start_at', 'asc')
            ->get();

        return NoteResource::collection($notes);
    }

    private function getDisplayedNotes()
    {
        $notes = Note::where('start_at', '>=', date('Y-m-d'))
            ->orderBy('start_at', 'asc')
            ->limit(5)
            ->get();

        return NoteResource::collection($notes);
    }

    public function deleteNote(Request $request)
    {
        $note = Note::find($request->id);
        if ($note->delete()) {
            event(new HomeEvent(['data' => $this->getDisplayedNotes(), 'event_type' => 'notes']));
            event(new HomeEvent(['data' => $this->getAllFutureNotesArray(), 'event_type' => 'futureNotes']));

            return response()->json(['type' => 'success', 'message' => __('basic.deleted-successfully')]);
        }

        return response()->json(['type' => 'error', 'message' => __('basic.error-occurred')]);
    }
}
