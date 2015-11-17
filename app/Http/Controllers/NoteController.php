<?php

namespace Codice\Http\Controllers;

use Codice\Note;
use Input;
use Redirect;
use Validator;
use View;

class NoteController extends Controller
{
    private $rules = [
        'content' => 'required',
        'expires_at' => 'date',
    ];

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

    /**
     * Display a form for adding new note.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate()
    {
        return View::make('note.create');
    }

    /**
     * Processes a form for creating new note.
     *
     * @return \Illuminate\Http\Response
     */
    public function postCreate()
    {
        $validator = Validator::make(Input::all(), $this->rules);

        if ($validator->passes()) {
            Note::create([
                // @todo: remove hardcoded user ID
                'user_id' => 1,
                'content' => Input::get('content'),
                'status' => 0,
                'expires_at' => Input::has('expires_at') ? strtotime(Input::get('expires_at')) : null,
            ]);

            return Redirect::route('index')->with('message', trans('note.create.success'));
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }
}
