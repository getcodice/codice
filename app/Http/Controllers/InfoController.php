<?php

namespace Codice\Http\Controllers;

use Codice\Core\Codice;
use Codice\Label;
use Codice\Note;
use Codice\Plugins\Filter;
use League\CommonMark\CommonMarkConverter;
use Redirect;
use View;

class InfoController extends Controller
{
    /**
     * Displays "About Codice" page.
     *
     * GET /about (as about)
     */
    public function getAbout()
    {
        $version = (new Codice)->getVersion();

        /**
         * Filters Codice core version in human readable form.
         *
         * @since 0.6.0
         *
         * @return string
         */
        $displayVersion = Filter::call('core.version.display', $version);

        return View::make('info.about', [
            'changelog' => $this->fetchChangelog($version),
            'title' => trans('info.about.title'),
            'version' => $displayVersion,
        ]);
    }

    /**
     * Checks for software updates.
     *
     * GET /about/check-updates (as about.updates)
     */
    public function getUpdates()
    {
        $releaseData = @file_get_contents(config('services.website.api') . '/releases/latest');
        $version = @json_decode($releaseData, true)['version'];

        if (!$version) {
            return Redirect::route('about')->with([
                'message' => trans('info.updates.error'),
                'message_type' => 'danger',
            ]);
        }

        $version = substr($version, 1); // Strip "v" from the front

        if (version_compare($version, (new Codice)->getVersion(), 'gt')) {
            return Redirect::route('about')->with([
                'message' => trans('info.updates.available', [
                    'url' => config('services.website.url'),
                    'version' => $version,
                ]),
                'message_type' => 'warning',
                'message_raw' => true,
            ]);
        } else {
            return Redirect::route('about')->with([
                'message' => trans('info.updates.none'),
                'message_type' => 'info',
            ]);
        }
    }

    /**
     * Displays statistics.
     *
     * GET /stats (as stats)
     */
    public function getStats()
    {
        $stats = [
            'all' => Note::mine()->count(),
            'done' => Note::whereStatus(1)->mine()->count(),
            'pending' => Note::whereStatus(0)->whereRaw('expires_at > NOW()')->mine()->count(),
            'expired' => Note::whereStatus(0)->whereRaw('expires_at < NOW()')->mine()->count(),
            'labels' => Label::mine()->count(),
            'notes_by_label' => '<a href="' . route('labels') . '">' . trans('info.stats.show') . '</a>',
        ];

        return View::make('info.stats', [
            'stats' => $stats,
            'title' => trans('info.stats.title'),
        ]);
    }

    /**
     * Fetch changelog from the official Codice API and parse it.
     *
     * @param  string $version Version to obtain changelog for
     * @return string
     */
    private function fetchChangelog($version)
    {
        $codice = new Codice();

        if (!$codice->isVersionStable()) {
            return trans('info.about.changelog-dev');
        }

        $releaseData = @file_get_contents(config('services.website.api') . "/releases/v$version");
        $changelog = @json_decode($releaseData, true)['changelog'];

        if (!$changelog) {
            return trans('info.about.changelog-error');
        }

        return (new CommonMarkConverter())->convertToHtml($changelog);
    }
}
