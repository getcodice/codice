<?php

use Codice\Label;
use Codice\Note;
use Codice\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LabelsTest extends TestCase
{
    use DatabaseMigrations;

    public function testAssigningLabels()
    {
        // Create new user and log it in (processNewLabels() uses Auth::id())
        factory(User::class)->create();
        Auth::loginUsingId(1);

        factory(Label::class, 3)->create();
        $note = factory(Note::class)->create();

        $labels = [
            0 => "1", // numeric ID
        ];

        $note->reTag($labels);

        $this->seeInDatabase('label_note', [
            'label_id' => 1,
            'note_id' => 1
        ]);
    }

    public function testAssigningAndCreatingLabels()
    {
        // Create new user and log it in (processNewLabels() uses Auth::id())
        factory(User::class)->create();
        Auth::loginUsingId(1);

        factory(Label::class, 3)->create();
        $note = factory(Note::class)->create();

        $labels = [
            0 => "1", // numeric ID
            // non-numeric (string) means that user created new label
            // (this is how select2.js behaves)
            1 => "new label",
        ];

        $note->reTag($labels);

        $this->seeInDatabase('label_note', [
            'label_id' => 1,
            'note_id' => 1
        ]);

        $this->seeInDatabase('labels', [
            'name' => 'new label'
        ]);
    }

    public function testCreatingLabelWithInvalidColor()
    {
        factory(Label::class)->create(['color' => 'invalid']);

        $this->seeInDatabase('labels', [
            'user_id' => 1,
            'color' => 1
        ]);
    }
}
