<?php

class CalendarTest extends TestCase
{
    public function monthDataProvider()
    {
        return [
            'sunday'    => [ 5, 2016, '2016.04.25', '2016.06.05'],
            'monday'    => [ 8, 2016, '2016.08.01', '2016.09.04'],
            'tuesday'   => [ 3, 2016, '2016.02.29', '2016.04.03'],
            'wednesday' => [ 6, 2016, '2016.05.30', '2016.07.03'],
            'thursday'  => [ 9, 2016, '2016.08.29', '2016.10.02'],
            'friday'    => [ 4, 2016, '2016.03.28', '2016.05.01'],
            'saturday'  => [10, 2016, '2016.09.26', '2016.11.06'],
        ];
    }

    /**
     * Test Calendar::createMonthArray().
     *
     * @dataProvider monthDataProvider()
     * @param int $month
     * @param int $year
     * @param string $first Day expected to be the first one in calendar for given month
     * @param string $last  Day expected to be the last one in calendar for given month
     */
    public function testDateRange($month, $year, $first, $last)
    {
        $array = (new \Codice\Calendar($month, $year))->createMonthArray();
        $this->assertSame($first, iterator_to_array(reset($array))[0]->format('Y.m.d'));
        $this->assertSame($last, iterator_to_array(end($array))[6]->format('Y.m.d'));
    }
}
