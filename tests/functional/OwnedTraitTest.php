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

        $note = Note::findMine(1);

        $this->assertEquals('users note', $note->content_raw);
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

        $this->assertEquals('JohnDoe', $note->user->name);
    }

    /**
     * AFAIR the bug was fixed in newer versions of Laravel and database seeding
     * can also be done in setUp() there.
     *
     * @todo Review when upgrading
     */
    private function setUpTest()
    {
        // Note ID: 1
        Note::create([
            'user_id' => 1,
            'content' => 'users note'
        ]);

        // Note ID: 2
        Note::create([
            'user_id' => 1,
            'content' => 'another note'
        ]);

        // Note ID: 3
        Note::create([
            'user_id' => 2,
            'content' => 'secret note of other user'
        ]);

        // User ID: 1
        User::create([
            'name' => 'JohnDoe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('johndoe123'),
            'options' => User::$defaultOptions,
        ]);

        Auth::loginUsingId(1);
    }
}
