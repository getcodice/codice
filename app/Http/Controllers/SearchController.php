<?php

namespace Codice\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Codice\Core\SearchFilterCompiler;
use Codice\Note;
use Codice\Plugins\Filter;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Displays search results.
     *
     * GET /search (as search)
     */
    public function getIndex(Request $request)
    {
        $query = trim($request->query('query'));

        if (empty($query)) {
            return redirect()->route('index');
        }

        $perPage = Auth::user()->options['notes_per_page'];

        $compiler = new SearchFilterCompiler($query);

        if ($compiler->isFilter()) {
            $where = $compiler->buildClause();
        } else {
            $where = Filter::call('core.search.clause', $query);
        }

        $notes = Note::with('labels')->mine()->whereRaw($where)->latest()->simplePaginate($perPage);

        $tip = $this->randomTip();
        $tip = '<a href="' . route('search') . '?query=' . $tip['filter'] . '">' . $tip['id'] . '</a>';

        return view('search.index', [
            'notes' => $notes,
            'query' => $query,
            'tip' => $tip,
            'title' => trans('search.title'),
        ]);
    }

    /**
     * Returns random tip displayed below the search results.
     */
    protected function randomTip()
    {
        $tips = [
            [
                'id' => 'done',
                'filter' => 'status:done'
            ],
            [
                'id' => 'undone',
                'filter' => 'status:undone'
            ],
            [
                'id' => 'yesterday',
                'filter' => 'date:' . Carbon::yesterday()->format(trans('app.date'))
            ],
        ];

        $tips = array_map(function($tip) {
            $tip['id'] = trans('search.tips.' . $tip['id']);
            $tip['filter'] = urlencode($tip['filter']);

            return $tip;
        }, $tips);

        return $tips[mt_rand(0, count($tips) - 1)];
    }
}
