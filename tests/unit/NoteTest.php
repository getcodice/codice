<?php

use Carbon\Carbon;
use Codice\Note;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
}
