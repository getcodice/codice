<?php

namespace Codice\Http\Controllers;

use Codice\Codice;
use View;

class InfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Displays "About Codice" page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAbout()
    {
        return View::make('info.about', [
            'title' => trans('info.about.title'),
            'version' => Codice::VERSION,
        ]);
    }
}
