<?php

namespace Tests\Functional;

use Auth;
use Codice\Note;
use Codice\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testDisplayingIndex()
    {
        $this->setUpTest();

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testDisplayingNote()
    {
        $this->setUpTest();

        factory(Note::class)->create(['user_id' => 1]);

        $response = $this->get('note/1');

        $response->assertStatus(200);
    }

    public function testDisplayingMissingNote()
    {
        $this->setUpTest();

        $response = $this->get('/note/5');

        $response->assertStatus(302);
    }

    public function testDisplayingNoteCreationPage()
    {
        $this->setUpTest();

        $response = $this->get('/create');

        $response->assertStatus(200);
    }

    public function testDisplayingNoteEditionPage()
    {
        $this->setUpTest();

        $response = $this->get('/note/1/edit');

        $response->assertStatus(200);
    }

    public function testTogglingNote()
    {
        $this->setUpTest();

        $this->get('/note/1/toggle');

        // Welcome note is marked as done by default
        $this->assertDatabaseHas('notes', [
            'id' => 1,
            'status' => 0
        ]);
    }

    public function testRemovingNote()
    {
        $this->setUpTest();

        $this->get('/note/1/remove');

        $this->assertNull(Note::first());
    }

    private function setUpTest()
    {
        factory(User::class)->create();
        Auth::loginUsingId(1);

        // Note is inserted automagically due to codice_add_welcome_note action
    }
}
