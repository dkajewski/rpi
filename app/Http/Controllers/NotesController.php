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
}
