<?php

namespace Codice\Http\Controllers;

use Auth;
use Codice\Note;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of notes.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return View::make('index', [
            'notes' => Note::logged()->orderBy('created_at', 'desc')->get(),
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
                'user_id' => Auth::id(),
                'content' => Input::get('content'),
                'status' => 0,
                'expires_at' => Input::has('expires_at') ? strtotime(Input::get('expires_at')) : null,
            ]);

            return Redirect::route('index')->with('message', trans('note.create.success'));
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Display a form for editing a note.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEdit($id)
    {
        try {
            $note = Note::logged()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return Redirect::route('index')->with('message', trans('note.not-found'))
                ->with('message_type', 'danger');
        }

        return View::make('note.edit', [
            'note' => $note,
        ]);
    }

    /**
     * Processes a form for editing note.
     *
     * @return \Illuminate\Http\Response
     */
    public function postEdit($id)
    {
        try {
            $note = Note::logged()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return Redirect::route('index')->with('message', trans('note.not-found'))
                ->with('message_type', 'danger');
        }

        $validator = Validator::make(Input::all(), $this->rules);

        if ($validator->passes()) {
            $note->content = Input::get('content');
            $note->expires_at = Input::has('expires_at') ? strtotime(Input::get('expires_at')) : null;
            $note->save();

            return Redirect::route('index')->with('message', trans('note.edit.success'));
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Change status of a note.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getChangeStatus($id)
    {
        try {
            $note = Note::logged()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return Redirect::route('index')->with('message', trans('note.not-found'))
                ->with('message_type', 'danger');
        }

        $newStatus = (int) !$note->status;

        $note->status = $newStatus;
        $note->save();

        $message = $newStatus === 1 ? 'note.done.done' : 'note.done.undone';

        return Redirect::route('index')->with('message', trans($message));
    }

    /**
     * Delete a note.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getRemove($id)
    {
        try {
            $note = Note::logged()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return Redirect::route('index')->with('message', trans('note.not-found'))
                ->with('message_type', 'danger');
        }

        $note->delete();

        return Redirect::route('index')->with('message', trans('note.removed'));
    }
}
