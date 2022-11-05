<?php

namespace App\Http\Controllers;

use App\Events\DefaultEvent;
use App\Events\NotesEvent;
use App\Note;
use App\Http\Resources\Note as NoteResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NotesController extends Controller
{
    public function index()
    {
        $resource = $this->getDisplayedNotes();

        return response()->json(['data' => $resource]);
    }

    public function store(Request $request): JsonResponse
    {
        $note = new Note();
        $note->description = $request->description ?? '';
        $note->start_at = date('Y-m-d', strtotime($request->start_at)) ?? '';
        $note->end_at = date('Y-m-d', strtotime($request->end_at)) ?? '';
        if ($note->save()) {
            event(new NotesEvent($this->getDisplayedNotes()));
            event(new DefaultEvent(['data' => $this->getAllFutureNotesArray(), 'event_type' => 'futureNotes']));
            return response()->json(['type' => 'success', 'message' => __('basic.saved-successfully').': '.$note->description]);
        }

        return response()->json(['type' => 'error', 'message' => __('basic.error-occurred')]);
    }

    public function getAllFutureNotes(): JsonResponse
    {
        $resource = $this->getAllFutureNotesArray();

        return response()->json(['data' => $resource]);
    }

    private function getAllFutureNotesArray(): AnonymousResourceCollection
    {
        $notes = Note::where('start_at', '>=', date('Y-m-d'))
            ->orderBy('start_at', 'asc')
            ->get();

        return NoteResource::collection($notes);
    }

    public function getDisplayedNotes(): AnonymousResourceCollection
    {
        $notes = Note::where('start_at', '>=', date('Y-m-d'))
            ->orderBy('start_at', 'asc')
            ->limit(5)
            ->get();

        return NoteResource::collection($notes);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteNote(Request $request): JsonResponse
    {
        $note = Note::find($request->id);
        if ($note->delete()) {
            event(new NotesEvent($this->getDisplayedNotes()));
            event(new DefaultEvent(['data' => $this->getAllFutureNotesArray(), 'event_type' => 'futureNotes']));

            return response()->json(['type' => 'success', 'message' => __('basic.deleted-successfully')]);
        }

        return response()->json(['type' => 'error', 'message' => __('basic.error-occurred')]);
    }
}
