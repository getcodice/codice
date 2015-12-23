<?php

namespace Codice\Http\Controllers;

use Auth;
use Codice\Label;
use Codice\Note;
use Input;
use Redirect;
use Validator;
use View;

class LabelController extends Controller
{
    private $rules = [
        'name' => 'required',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of labels.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $labels = Label::selectRaw('labels.*, COUNT(notes.id) AS count')
            ->leftJoin('label_note', 'labels.id', '=', 'label_note.label_id')
            ->leftJoin('notes', 'label_note.note_id', '=', 'notes.id')
            ->where('labels.user_id', '=', Auth::id())
            ->groupBy('labels.id')
            ->orderBy('count', 'desc')
            ->get();

        return View::make('label.index', [
            'colors' => config('labels.colors'),
            'labels' => $labels,
            'title' => trans('labels.index.title'),
        ]);
    }

    /**
     * Display notes associated with given label.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getNotes($id)
    {
        // First, validate existence of label owned by current user
        $label = Label::findOwned($id);

        // Then fetch the notes associated with this label
        $perPage = Auth::user()->options['notes_per_page'];

        $notes = Note::whereHas('labels', function ($q) use ($id) {
            $q->where('id', $id);
        })->logged()->orderBy('created_at', 'desc')->simplePaginate($perPage);

        return View::make('label.notes', [
            'label' => $label,
            'notes' => $notes,
            'quickform_label' => $id,
            'quickform_labels' => Label::orderBy('name')->lists('name', 'id'),
            'title' => trans('labels.notes.title', ['label' => $label->name]),
        ]);
    }

    /**
     * Display a form for adding new label.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate()
    {
        return View::make('label.create', [
            'title' => trans('labels.create.title'),
        ]);
    }

    /**
     * Process a form for creating new label.
     *
     * @return \Illuminate\Http\Response
     */
    public function postCreate()
    {
        $validator = Validator::make(Input::all(), $this->rules);

        if ($validator->passes()) {
            $label = Label::create([
                'user_id' => Auth::id(),
                'name' => Input::get('name'),
                'color' => Input::get('color'),
            ]);

            event('label.save.create', [$label]);

            return Redirect::route('labels')->with('message', trans('labels.create.success'));
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Display a form for editing a label.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEdit($id)
    {
        return View::make('label.edit', [
            'label' => Label::findOwned($id),
            'title' => trans('labels.edit.title'),
        ]);
    }

    /**
     * Process a form for editing label.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit($id)
    {
        $label = Label::findOwned($id);

        $validator = Validator::make(Input::all(), $this->rules);

        if ($validator->passes()) {
            $label->name = Input::get('name');
            $label->color = Input::get('color');
            $label->save();

            event('label.save.edit', [$label]);

            return Redirect::route('labels')->with('message', trans('labels.edit.success'));
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Delete a label.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getRemove($id)
    {
        $label = Label::findOwned($id);
        $label->delete();

        event('label.drop', [$label]);

        return Redirect::route('labels')->with('message', trans('labels.removed'));
    }
}
