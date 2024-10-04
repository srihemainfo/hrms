<?php

namespace App\Exceptions;

use Throwable;
use SpreadsheetReader;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            // Handle CSRF token mismatch by redirecting to the user's dashboard
            return redirect('/admin');
        }
        if ($exception instanceof MethodNotAllowedHttpException) {

            return redirect('/admin');
        }
        if ($exception instanceof SpreadsheetReader) {

            return redirect('/admin');
        }

        return parent::render($request, $exception);
    }
}
