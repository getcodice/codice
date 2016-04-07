<?php

namespace Codice;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use \DatePeriod;

class Calendar
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * @author Kacper "Kadet" Donat <kacper@kadet.net>
     * @return array
     *
     * Probably the cleanest solution to generate a calendar in PHP ever written.
     */
    public function createMonthArray()
    {
        $current = Carbon::createFromDate($this->year, $this->month, 1);
        $current->subDays(6 - $current->dayOfWeek);

        $weeks = [];
        do {
            $weeks[] = new DatePeriod(clone $current, CarbonInterval::day(), $current->addWeek());
        } while ($current->month == $this->month);

        return $weeks;
    }
}
