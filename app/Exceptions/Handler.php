<?php

namespace Codice\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Redirect;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use View;

class Handler extends ExceptionHandler
{
    /**
     * {@inheritdoc}
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof HttpException) {
            $code = $e->getStatusCode();

            $viewName = View::exists("error.$code") ? "error.$code" : 'error.generic';

            return response()->view($viewName, [
                'code' => $code,
            ], $code);
        }

        if ($e instanceof TokenMismatchException) {
            return Redirect::back()->with('message', trans('error.token-mismatch'))
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
