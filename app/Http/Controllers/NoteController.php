<?php

namespace Codice\Http\Controllers;

use Auth;
use Codice\Label;
use Codice\Note;
use Codice\Reminder;
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
            'quickform_labels' => Label::logged()->orderBy('name')->lists('name', 'id'),
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
            'labels' => Label::logged()->orderBy('name')->lists('name', 'id'),
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
            $labels = $this->processNewLabels($labels);
            $note->labels()->sync($labels);

            if (Input::has('reminder_email')) {
                Reminder::addReminder(
                    $note,
                    strtotime(Input::get('reminder_email')),
                    Reminder::TYPE_EMAIL
                );
            }

            if (Input::has('quickform_label') && is_numeric(Input::get('quickform_label'))) {
                $response = Redirect::route('label', ['id' => Input::get('quickform_label')]);
            } else {
                $response = Redirect::route('index');
            }

            return $response->with('message', trans('note.create.success'));
        } else {
            return Redirect::route('note.create')->withErrors($validator)->withInput();
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
            'labels' => Label::logged()->orderBy('name')->lists('name', 'id'),
            'note' => $note,
            'note_labels' => $note->labels()->lists('id')->toArray(),
            'reminder_email' => $note->reminder(Reminder::TYPE_EMAIL),
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
            $labels = $this->processNewLabels($labels);
            $note->labels()->sync($labels);

            $this->processReminder($note, Reminder::TYPE_EMAIL, Input::get('reminder_email'));

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
     * Display single note.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getNote($id)
    {
        return View('note.note', [
            'note' => Note::findOwned($id),
            'single' => true,
        ]);
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
    public function getUpcoming($mode = null)
    {
        $perPage = Auth::user()->options['notes_per_page'];

        $query = Note::logged()
            ->where('status', 0)
            ->whereNotNull('expires_at');

        if ($mode != 'with-expired') {
            $query->whereRaw('expires_at > NOW()');
        }

        $notes = $query->orderBy('expires_at', 'asc')->simplePaginate($perPage);

        return View::make('note.upcoming', [
            'mode' => $mode,
            'notes' => $notes,
            'title' => trans('note.upcoming.title'),
        ]);
    }

    private function processNewLabels($labels)
    {
        $existingLabels = Label::orderBy('name')->lists('id')->toArray();

        foreach ($labels as $labelKey =>$label) {
            if (!in_array($label, $existingLabels)) {
                $newLabel = Label::create([
                    'user_id' => Auth::id(),
                    'name' => $label,
                    'color' => 1, // Let's give it default color
                ]);

                // We need to replace label name with its ID in $labels in order to get
                // Laravel's sync() working and happy...
                unset($labels[$labelKey]);
                $labels[] = $newLabel->id;
            }
        }

        return $labels;
    }

    private function processReminder(Note $note, $type, $input)
    {
        $reminder = $note->reminder($type);

        // Note has a reminder and form has it - update existing one
        if (!empty($input) && !empty($reminder)) {
            $reminder->remind_at = strtotime($input);
            $reminder->save();
        // Note doesn't have a reminder but it is set in form - just add one
        } elseif (!empty($input) && $reminder === null) {
            Reminder::addReminder($note, strtotime($input), $type);
        // Note have a reminder but it's not set in form - remove reminder
        } elseif (empty($input) && !empty($reminder)) {
            $reminder->delete();
        }
    }
}
