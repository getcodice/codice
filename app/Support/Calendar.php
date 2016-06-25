<?php

namespace Codice\Support;

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
     * @return DatePeriod[]
     *
     * Probably the cleanest solution to generate a calendar in PHP ever written.
     */
    public function createMonthArray()
    {
        $current = Carbon::createFromDate($this->year, $this->month, 1);
        $current->subDays($current->dayOfWeek - 1 < 0 ? $current->dayOfWeek + 6 : $current->dayOfWeek - 1);

        $weeks = [];
        do {
            $weeks[] = new DatePeriod(clone $current, CarbonInterval::day(), $current->addWeek());
        } while ($current->month == $this->month);

        return $weeks;
    }
}
