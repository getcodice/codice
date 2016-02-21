<?php

namespace Codice\Http\Controllers;

use Auth;
use Codice\Label;
use Codice\Note;
use Codice\Reminders\ReminderService;
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
     * Process a form for creating new note.
     *
     * @return \Illuminate\Http\Response
     */
    public function postCreate()
    {
        $input = Input::all();

        $validator = Validator::make($input, $this->rules);

        if ($validator->passes()) {
            $note = Note::create([
                'user_id' => Auth::id(),
                'content' => Input::get('content'),
                'status' => 0,
                'expires_at' => Input::has('expires_at') ? strtotime(Input::get('expires_at')) : null,
            ]);

            event('note.save.create', [$note]);

            $labels = Input::get('labels', []);
            $labels = $this->processNewLabels($labels);
            $note->labels()->sync($labels);

            // Handle reminders
            foreach (ReminderService::getRegisteredKeys() as $reminderID) {
                if (Input::has("reminder_$reminderID")) {
                    ReminderService::get($reminderID)->set($note, $input);
                }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEdit($id)
    {
        $note = Note::findOwned($id);

        return View::make('note.edit', [
            'labels' => Label::logged()->orderBy('name')->lists('name', 'id'),
            'note' => $note,
            'note_labels' => $note->labels()->lists('id')->toArray(),
            // @todo: temporary
            'reminder_email' => $note->reminder('email'),
            'title' => trans('note.edit.title'),
        ]);
    }

    /**
     * Process a form for editing note.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit($id)
    {
        $note = Note::findOwned($id);
        $input = Input::all();

        $validator = Validator::make($input, $this->rules);

        if ($validator->passes()) {
            $note->content = Input::get('content');
            $note->expires_at = Input::has('expires_at') ? strtotime(Input::get('expires_at')) : null;
            $note->save();

            event('note.save.edit', [$note]);

            $labels = Input::get('labels', []);
            $labels = $this->processNewLabels($labels);
            $note->labels()->sync($labels);

            // Handle reminders
            foreach (ReminderService::getRegisteredKeys() as $reminderID) {
                ReminderService::get($reminderID)->process($note, $input);
            }

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
        $note->saveWithoutTouching();

        // Read target status using $note->status
        event('note.changeStatus', [$note]);

        $message = $newStatus === 1 ? 'note.done.done' : 'note.done.undone';

        return Redirect::back()->with('message', trans($message));
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

        event('note.drop', [$note]);

        return Redirect::back()->with('message', trans('note.removed'));
    }

    /**
     * Display only upcoming undone notes.
     *
     * @param  string  $mode
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

    /**
     * Process new labels added while creating/editing a note.
     *
     * @param  array  $labels
     * @return array
     */
    private function processNewLabels(array $labels)
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
}
