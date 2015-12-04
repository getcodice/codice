<?php

namespace Codice\Http\Controllers;

use Codice\Codice;
use Redirect;
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

    /**
     * Check for software updates.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUpdates()
    {
        $version = @file_get_contents('http://codice.eu/version.txt');
        if ($version == false) {
            return Redirect::route('about')->with([
                'message' => trans('info.updates.error'),
                'message_type' => 'danger',
            ]);
        }

        $version = trim($version);

        if (version_compare($version, Codice::VERSION, 'gt')) {
            return Redirect::route('about')->with([
                'message' => trans('info.updates.available', ['version' => $version]),
                'message_type' => 'info',
                'message_raw' => true,
            ]);
        } else {
            return Redirect::route('about')->with([
                'message' => trans('info.updates.none'),
                'message_type' => 'success',
            ]);
        }

        return View::make('info.about', [
            'changelog' => $changelog,
            'title' => trans('info.about.title'),
            'version' => Codice::VERSION,
        ]);
    }
}
