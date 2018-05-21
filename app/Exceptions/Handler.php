<?php

namespace Codice\Exceptions;

use App;
use Auth;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Redirect;
use Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * {@inheritdoc}
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof HttpException) {
            $code = $e->getStatusCode();

            if (Auth::check()) {
                $view = 'authorized';
            } else {
                $view = 'unauthorized';
                // We don't know language of the visitor so let's provide English version
                App::setLocale('en');
            }

            return Response::view("error.$view", [
                'error' => "HTTP $code",
                'message' => trans("app.error.http.$code"),
                'title' => "HTTP $code",
            ], $code);
        }

        if ($e instanceof TokenMismatchException) {
            return Redirect::back()->with('message', trans('app.error.token-mismatch'))
                ->with('message_type', 'danger')
                ->withInput();
        }

        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        return parent::render($request, $e);
    }

    /**
     * {@inheritdoc}
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
