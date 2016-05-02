<?php

use Carbon\Carbon;

class HelpersTest extends TestCase
{
    /**
     * Data for testing pad_zero() helper.
     *
     * @return array
     */
    public function padZeroDataProvider() {
        return [
            [0, '00'],
            [1, '01'],
            [4, '04'],
            [9, '09'],
            [10, '10'],
            [112, '112'],
            [512, '512'],
            [9999, '9999'],
        ];
    }

    /**
     * Test calendar_class() helper.
     *
     * @return void
     */
    public function testCalendarClassHelper()
    {
        $dayWithNoEvents = Carbon::createFromDate(2015, 12, 24);
        $dayWithCreatedEvent = Carbon::createFromDate(2015, 12, 25);
        $dayWithExpiringEvent = Carbon::createFromDate(2015, 12, 26);
        $dayWithCreatedAndExpiringEvent = Carbon::createFromDate(2015, 12, 27);

        $events = [];
        $events['12-25']['created'] = true;
        $events['12-26']['expiring'] = true;
        $events['12-27']['created'] = true;
        $events['12-27']['expiring'] = true;

        $this->assertEquals('', calendar_class($dayWithNoEvents, 12, $events));
        $this->assertEquals('blur', calendar_class($dayWithNoEvents, 11, $events));
        $this->assertEquals('created', calendar_class($dayWithCreatedEvent, 12, $events));
        $this->assertEquals('expiring', calendar_class($dayWithExpiringEvent, 12, $events));
        $this->assertEquals('created expiring', calendar_class($dayWithCreatedAndExpiringEvent, 12, $events));
    }

    /**
     * Test helper for fontawesome icons.
     *
     * @return void
     */
    public function testIconHelper()
    {
        $this->assertEquals('<span class="fa fa-foo"></span>', icon('foo'));
        $this->assertEquals('<span class="fa fa-foo bar"></span>', icon('foo bar'));
    }

    /**
     * Test pad_zero() helper.
     *
     * @dataProvider padZeroDataProvider
     * @param $input Input for the function
     * @param $excepted Excepted output
     * @return void
     */
    public function testPadZeroHelper($input, $excepted)
    {
        $actual = pad_zero($input);
        $this->assertSame($excepted, $actual);
    }
}
