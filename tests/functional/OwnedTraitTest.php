<?php

use Codice\Note;
use Codice\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Exception\HttpResponseException;

class OwnwedTraitTest extends TestCase
{
    use DatabaseMigrations;

    public function testFetchingOwnedNote()
    {
        $this->setUpTest();

        $expectedNote = Note::findMine(1);
        $note = Note::find(1);

        $this->assertEquals($note->content_raw, $expectedNote->content_raw);
    }

    public function testLimitingToOwnedScope()
    {
        $this->setUpTest();

        $notes = Note::mine()->get();

        $this->assertEquals(2, count($notes));
    }

    public function testFetchingOtherUsersNote()
    {
        $this->setUpTest();

        $this->setExpectedException(HttpResponseException::class);

        $note = Note::findMine(3);
    }

    public function testUserRelationOnOwnedNote()
    {
        $this->setUpTest();

        $note = Note::findMine(1);
        $user = User::first();

        $this->assertEquals($user->name, $note->user->name);
    }

    /**
     * AFAIR the bug was fixed in newer versions of Laravel and database seeding
     * can also be done in setUp() there.
     *
     * @todo Review when upgrading
     */
    private function setUpTest()
    {
        factory(Note::class, 2)->create();
        factory(Note::class)->create(['user_id' => 2]);

        factory(User::class)->create();
        Auth::loginUsingId(1);
    }
}
