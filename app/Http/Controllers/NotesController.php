<?php

namespace App\Http\Controllers;

use App\Note;
use App\Http\Resources\Note as NoteResource;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function index()
    {
        $notes = Note::where('start_at', '>=', date('Y-m-d'))
            ->orderBy('start_at', 'asc')
            ->limit(5)
            ->get();
        $resource = NoteResource::collection($notes);

        return response()->json(['data' => $resource]);
    }

    public function store(Request $request)
    {
        $note = new Note();
        $note->description = $request->description ?? '';
        $note->start_at = date('Y-m-d', strtotime($request->start_at)) ?? '';
        $note->end_at = date('Y-m-d', strtotime($request->end_at)) ?? '';
        if ($note->save()) {
            return response()->json(['type' => 'success', 'message' => __('basic.saved-successfully').': '.$note->description]);
        }

        return response()->json(['type' => 'error', 'message' => __('basic.error-occurred')]);
    }

    public function getAllFutureNotes()
    {
        $notes = Note::where('start_at', '>=', date('Y-m-d'))
            ->orderBy('start_at', 'asc')
            ->get();
        $resource = NoteResource::collection($notes);

        return response()->json(['data' => $resource]);
    }

    public function deleteNote(Request $request)
    {
        $note = Note::find($request->id);
        if ($note->delete()) {
            return response()->json(['type' => 'success', 'message' => __('basic.deleted-successfully')]);
        }

        return response()->json(['type' => 'error', 'message' => __('basic.error-occurred')]);
    }
}
