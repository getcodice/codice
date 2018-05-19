<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Codice\Note;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Data for testing Note::getStateAttribute().
     *
     * @see doclobck for method below (testNoteState()) for array structure
     * @return array
     */
    public function stateDataProvider() {
        return [
            ['-1 month', false, 'danger'],
            ['-10 days', false, 'danger'],
            ['-1 hour', false, 'danger'],
            ['-1 minute', false, 'danger'],
            ['+2 hours', false, 'warning'],
            ['+23 hours', false, 'warning'],
            ['+25 hours', false, 'info'],
            ['+2 days', false, 'info'],
            ['+14 days', false, 'info'],
            ['-1 month', true, 'success'],
            ['-10 hours', true, 'success'],
            [null, true, 'success'],
            [null, false, 'default'],
        ];
    }

    /**
     * Test Note::getStateAttribute().
     *
     * @dataProvider stateDataProvider()
     * @param $expiresAt string|null Time modifier which should be added to get expires_at. If null is passed,
     *                               expires_at is not going to be set.
     * @param $status bool Whether note is marked as done or not
     * @param $expectedState string Status which should be returned
     * @return void
     */
    public function testNoteState($expiresAt, $status, $expectedState)
    {
        $note = new Note;
        $note->user_id = 1;
        $note->content = 'foobar';
        $note->content_raw = 'foobar';
        $note->status = (int) $status;
        $note->expires_at = is_null($expiresAt) ? null : Carbon::now()->modify($expiresAt);
        $note->save();

        $this->assertEquals($expectedState, $note->state);
    }

    public function testSaveWithoutTouching()
    {
        // Create a note first
        $note = factory(Note::class)->create();

        $originalUpdatedAt = $note->updated_at;

        // Advance current time by one day
        Carbon::setTestNow(Carbon::tomorrow());

        // Update a note but without touching timestamps
        $note = Note::find(1);
        $note->status = 1;
        $note->saveWithoutTouching();

        $this->assertEquals($originalUpdatedAt, $note->updated_at);
    }
}
