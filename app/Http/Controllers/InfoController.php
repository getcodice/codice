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
        $changelog = @file_get_contents('http://codice.eu/changelog.txt');
        if ($changelog == false) {
            $changelog = trans('info.about.changelog-error');
        }

        return View::make('info.about', [
            'changelog' => $changelog,
            'title' => trans('info.about.title'),
            'version' => Codice::VERSION,
        ]);
    }
}
