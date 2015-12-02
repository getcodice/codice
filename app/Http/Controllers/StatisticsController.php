<?php

namespace Codice\Http\Controllers;

use Auth;
use Codice\Label;
use Codice\Note;
use View;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Displays statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $stats = [
            'all' => [
                'query' => Note::logged()->count(),
            ],
            'done' => [
                'class' => 'success',
                'query' => Note::whereStatus(1)->logged()->count(),
            ],
            'awaiting' => [
                'class' => 'info',
                'query' => Note::whereStatus(0)->whereRaw('expires_at > NOW()')->logged()->count(),
            ],
            'expired' => [
                'class' => 'danger',
                'query' => Note::whereStatus(0)->whereRaw('expires_at < NOW()')->logged()->count(),
            ],
            'labels' => [
                'query' => Label::logged()->count(),
            ],
        ];

        return View::make('statistics', [
            'stats' => $stats,
            'title' => trans('stats.title'),
        ]);
    }
}
