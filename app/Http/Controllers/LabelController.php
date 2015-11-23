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
        return View::make('label.index', [
            'colors' => config('labels.colors'),
            // @todo: getting number of labeled notes, sorting by it
            'labels' => Label::logged()->get(),
        ]);
    }

    /**
     * Display notes associated with given label.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNotes($id)
    {
        // First, validate existence of label owned by current user
        $label = Label::findOwned($id);

        // Then fetch the notes associated with this label
        $notes = Note::whereHas('labels', function ($q) use ($id) {
            $q->where('id', $id);
        })->logged()->orderBy('created_at', 'desc')->get();

        return View::make('label.notes', [
            'colors' => config('labels.colors'),
            'label' => $label,
            'notes' => $notes,
        ]);
    }

    /**
     * Display a form for adding new label.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate()
    {
        return View::make('label.create');
    }

    /**
     * Processes a form for creating new label.
     *
     * @return \Illuminate\Http\Response
     */
    public function postCreate()
    {
        $validator = Validator::make(Input::all(), $this->rules);

        if ($validator->passes()) {
            Label::create([
                'user_id' => Auth::id(),
                'name' => Input::get('name'),
                'color' => Input::get('color'),
            ]);

            return Redirect::route('labels')->with('message', trans('labels.create.success'));
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Display a form for editing a label.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEdit($id)
    {
        return View::make('label.edit', [
            'label' => Label::findOwned($id),
        ]);
    }

    /**
     * Processes a form for editing label.
     *
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

        return Redirect::route('labels')->with('message', trans('labels.removed'));
    }
}
