<?php

namespace Codice\Http\Controllers;

use Auth;
use Codice\Label;
use Codice\Note;
use DB;
use Illuminate\Http\Request;
use Redirect;
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
     * Displays listing of labels.
     *
     * GET /labels (as labels)
     */
    public function getIndex()
    {
        $tablePrefix = DB::getTablePrefix();

        $labels = Label::selectRaw("{$tablePrefix}labels.*, COUNT({$tablePrefix}notes.id) AS count")
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
     * Displays notes associated with given label.
     *
     * GET /label/{id} (as label)
     */
    public function getNotes($id)
    {
        // First, validate existence of label owned by current user
        $label = Label::findMine($id);

        // Then fetch the notes associated with this label
        $perPage = Auth::user()->options['notes_per_page'];

        $notes = Note::tagged($id)->with('labels')->mine()->latest()->simplePaginate($perPage);

        $quickform = quickform([
            'label' => $id,
            'target_url' => route('label', ['id' => $id]),
        ]);

        return View::make('label.notes', [
            'label' => $label,
            'notes' => $notes,
            'quickform' => $quickform,
            'title' => trans('labels.notes.title', ['label' => $label->name]),
        ]);
    }

    /**
     * Displays a form for adding new label.
     *
     * GET /labels/create (as label.create)
     */
    public function getCreate()
    {
        return View::make('label.create', [
            'title' => trans('labels.create.title'),
        ]);
    }

    /**
     * Processes a form for creating new label.
     *
     * POST /labels/create
     */
    public function postCreate(Request $request)
    {
        $this->validate($request, $this->rules);

        $label = Label::create([
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
            'color' => $request->input('color'),
        ]);

        return Redirect::route('labels')->with('message', trans('labels.create.success'));
    }

    /**
     * Displays a form for editing a label.
     *
     * GET /label/{id}/edit (as label.edit)
     */
    public function getEdit($id)
    {
        return View::make('label.edit', [
            'label' => Label::findMine($id),
            'title' => trans('labels.edit.title'),
        ]);
    }

    /**
     * Processes a form for editing label.
     *
     * POST /label/{id}/edit
     */
    public function postEdit(Request $request, $id)
    {
        $label = Label::findMine($id);

        $this->validate($request, $this->rules);

        $label->name = $request->input('name');
        $label->color = $request->input('color');
        $label->save();

        return Redirect::route('labels')->with('message', trans('labels.edit.success'));
    }

    /**
     * Removes a label.
     *
     * GET /label/{id}/remove (as label.remove)
     */
    public function getRemove($id)
    {
        $label = Label::findMine($id);
        $label->delete();

        return Redirect::route('labels')->with('message', trans('labels.removed'));
    }
}
