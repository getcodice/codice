<?php

namespace Codice\Http\Controllers;

use Auth;
use Codice\Label;
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
        $perPage = Auth::user()->options['notes_per_page'];

        return View::make('index', [
            'notes' => Note::logged()->orderBy('created_at', 'desc')->simplePaginate($perPage),
        ]);
    }

    /**
     * Display a form for adding new note.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate()
    {
        return View::make('note.create', [
            'labels' => Label::orderBy('name')->lists('name', 'id'),
            'title' => trans('note.create.title_head'),
        ]);
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
            $note = Note::create([
                'user_id' => Auth::id(),
                'content' => Input::get('content'),
                'status' => 0,
                'expires_at' => Input::has('expires_at') ? strtotime(Input::get('expires_at')) : null,
            ]);

            $labels = Input::get('labels', []);
            $note->labels()->sync($labels);

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
        $note = Note::findOwned($id);

        return View::make('note.edit', [
            'labels' => Label::orderBy('name')->lists('name', 'id'),
            'note' => $note,
            'note_labels' => $note->labels()->lists('id')->toArray(),
            'title' => trans('note.edit.title'),
        ]);
    }

    /**
     * Processes a form for editing note.
     *
     * @return \Illuminate\Http\Response
     */
    public function postEdit($id)
    {
        $note = Note::findOwned($id);

        $validator = Validator::make(Input::all(), $this->rules);

        if ($validator->passes()) {
            $note->content = Input::get('content');
            $note->expires_at = Input::has('expires_at') ? strtotime(Input::get('expires_at')) : null;
            $note->save();

            $labels = Input::get('labels', []);
            $note->labels()->sync($labels);

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
        $note = Note::findOwned($id);

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
        $note = Note::findOwned($id);
        $note->delete();

        return Redirect::route('index')->with('message', trans('note.removed'));
    }

    /**
     * Display only upcoming undone notes.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUpcoming()
    {
        $perPage = Auth::user()->options['notes_per_page'];

        $notes = Note::logged()
            ->where('status', 0)
            ->whereNotNull('expires_at')
            ->orderBy('expires_at', 'asc')
            ->simplePaginate($perPage);

        return View::make('index', [
            'notes' => $notes,
            'title' => trans('note.upcoming.title'),
        ]);
    }
}
