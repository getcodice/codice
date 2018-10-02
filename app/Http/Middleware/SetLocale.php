<?php

namespace Codice\Http\Middleware;

use App;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $this->determineLocale($request);

        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }

    protected function determineLocale(Request $request)
    {
        if ($this->auth->check()) {
            return $this->auth->user()['options']['language'];
        }

        // A bit naive check but should be enough for its purpose
        if (session()->has('install-lang') && str_contains($request->path(), 'install')) {
            return session()->get('install-lang');
        }

        return config('app.locale');
    }
}
