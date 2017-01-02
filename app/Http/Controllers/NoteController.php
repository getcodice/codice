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
            'notes' => Note::with('labels')->mine()->latest()->simplePaginate($perPage),
            'quickform' => quickform(),
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
            'labels' => Label::mine()->orderBy('name')->pluck('name', 'id'),
            'title' => trans('note.create.title_head'),
            'wysiwyg' => Auth::user()->options['wysiwyg'],
        ]);
    }

    /**
     * Process a form for creating new note.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(Request $request)
    {
        $input = $request->all();

        $this->validate($request, $this->rules, 'note.create');

        $note = Note::create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'expires_at' => $request->has('expires_at') ? strtotime($request->input('expires_at')) : null,
        ]);

        $note->reTag($request->input('labels', []));

        event('note.save.create', [$note]);

        // Handle reminders
        foreach (ReminderService::getRegisteredKeys() as $reminderID) {
            if ($request->has("reminder_$reminderID")) {
                ReminderService::get($reminderID)->set($note, $input);
            }
        }

        if ($request->has('quickform_target')) {
            $response = Redirect::to($request->input('quickform_target'));
        } else {
            $response = Redirect::route('index');
        }

        return $response->with('message', trans('note.create.success'));
    }

    /**
     * Display a form for editing a note.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Process a form for editing note.
     *
     * @param  Request $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(Request $request, $id)
    {
        $note = Note::findMine($id);
        $input = $request->all();

        $this->validate($request, $this->rules);

        $note->content = $request->input('content');
        $note->expires_at = $request->has('expires_at') ? strtotime($request->input('expires_at')) : null;
        $note->save();

        $note->reTag($request->input('labels', []));

        event('note.save.edit', [$note]);

        // Handle reminders
        foreach (ReminderService::getRegisteredKeys() as $reminderID) {
            ReminderService::get($reminderID)->process($note, $input);
        }

        return Redirect::route('index')->with('message', trans('note.edit.success'));
    }

    /**
     * Change status of a note.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getChangeStatus($id)
    {
        $note = Note::findMine($id);

        $newStatus = (int) !$note->status;

        $note->status = $newStatus;

        // If note is done, remove reminders for it
        if ($newStatus) {
            $note->reminders()->delete();
        }

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
        return View::make('note.note', [
            'note' => Note::findMine($id),
            'single' => true,
        ]);
    }

    /**
     * Show server-side confirmation before removing a note.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getRemoveConfirm($id)
    {
        return View::make('note.remove', [
            'id' => $id,
        ]);
    }

    /**
     * Delete a note.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getRemove($id)
    {
        $note = Note::findMine($id);
        $note->delete();

        event('note.drop', [$note]);

        return Redirect::back()->with('message', trans('note.removed'));
    }
}
