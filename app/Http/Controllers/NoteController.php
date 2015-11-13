<?php

namespace Codice\Http\Controllers;

use Codice\Note;
use View;

class NoteController extends Controller
{
    /**
     * Display a listing of notes.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return View::make('index', [
            // @todo: fix hardcoded user_id
            'notes' => Note::where('user_id', '=', 1)->orderBy('created_at', 'desc')->get(),
        ]);
    }
}
