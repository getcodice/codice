<?php

namespace Codice\Http\Controllers;

use Auth;
use Codice\Label;
use Codice\Note;
use Codice\Reminders\ReminderService;
use Illuminate\Http\Request;
use Redirect;
use View;

class NoteController extends Controller
{
    private $rules = [
        'content' => 'required',
        'expires_at' => 'date|nullable',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Displays listing of notes.
     *
     * GET / (as index)
     */
    public function getIndex()
    {
        $perPage = Auth::user()->options['notes_per_page'];

        return View::make('index', [
            'notes' => Note::with('labels')->mine()->latest()->simplePaginate($perPage),
            'quickform' => quickform(),
        ]);
    }

    /**
     * Displays a form for adding new note.
     *
     * GET /create (as note.create)
     */
    public function getCreate()
    {
        return View::make('note.create', [
            'labels' => Label::mine()->orderBy('name')->pluck('name', 'id'),
            'title' => trans('note.create.title_head'),
            'wysiwyg' => Auth::user()->options['wysiwyg'],
        ]);
    }

    /**
     * Processes a form for creating new note.
     *
     * POST /create
     */
    public function postCreate(Request $request)
    {
        $input = $request->all();

        $this->validate($request, $this->rules, 'note.create');

        $note = Note::create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'expires_at' => $request->filled('expires_at') ? strtotime($request->input('expires_at')) : null,
        ]);

        $note->reTag($request->input('labels', []));

        // Handle reminders
        foreach (ReminderService::getRegisteredKeys() as $reminderID) {
            if ($request->filled("reminder_$reminderID")) {
                ReminderService::get($reminderID)->set($note, $input);
            }
        }

        if ($request->filled('quickform_target')) {
            $response = Redirect::to($request->input('quickform_target'));
        } else {
            $response = Redirect::route('index');
        }

        return $response->with('message', trans('note.create.success'));
    }

    /**
     * Displays a form for editing a note.
     *
     * GET /note/{id}/edit (as note.edit)
     */
    public function getEdit($id)
    {
        $note = Note::findMine($id);

        return View::make('note.edit', [
            'labels' => Label::mine()->orderBy('name')->pluck('name', 'id'),
            'note' => $note,
            'note_labels' => $note->labels()->pluck('id')->toArray(),
            // @todo: temporary
            'reminder_email' => $note->reminder('email'),
            'title' => trans('note.edit.title'),
            'wysiwyg' => Auth::user()->options['wysiwyg'],
        ]);
    }

    /**
     * Processes a form for editing note.
     *
     * POST /note/{id}/edit
     */
    public function postEdit(Request $request, $id)
    {
        $note = Note::findMine($id);
        $input = $request->all();

        $this->validate($request, $this->rules);

        $note->content = $request->input('content');
        $note->expires_at = $request->filled('expires_at') ? strtotime($request->input('expires_at')) : null;
        $note->save();

        $note->reTag($request->input('labels', []));

        // Handle reminders
        foreach (ReminderService::getRegisteredKeys() as $reminderID) {
            ReminderService::get($reminderID)->process($note, $input);
        }

        return Redirect::route('index')->with('message', trans('note.edit.success'));
    }

    /**
     * Displays single note.
     *
     * GET /note/{id}
     */
    public function getNote($id)
    {
        return View::make('note.note', [
            'note' => Note::findMine($id),
            'single' => true,
        ]);
    }

    /**
     * Shows server-side confirmation before removing a note.
     *
     * GET /note/{id}/remove/confirm (as note.remove.confirm)
     */
    public function getRemoveConfirm($id)
    {
        return View::make('note.remove', [
            'id' => $id,
        ]);
    }

    /**
     * Removes a note.
     *
     * GET /note/{id}/remove (as note.remove)
     */
    public function getRemove($id)
    {
        $note = Note::findMine($id);
        $note->delete();

        return Redirect::back()->with('message', trans('note.removed'));
    }

    /**
     * Toggles note status.
     *
     * GET /note/{id}/toggle (as note.toggle)
     */
    public function getToggle($id)
    {
        $note = Note::findMine($id);

        $newStatus = (int) !$note->status;

        $note->status = $newStatus;

        // If note is done, remove reminders for it
        if ($newStatus) {
            $note->reminders()->delete();
        }

        $note->saveWithoutTouching();

        $message = $newStatus === 1 ? 'note.toggle.done' : 'note.toggle.undone';

        return Redirect::back()->with('message', trans($message));
    }
}
