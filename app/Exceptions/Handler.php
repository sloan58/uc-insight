<?php

namespace App\Exceptions;

use Log;
use Exception;
use Laracasts\Flash\Flash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        /*
         * Soap Fault Exceptions
         */
        if ($e instanceof SoapException) {
            switch($e) {
                case isset($e->message->faultcode) && $e->message->faultcode == 'HTTP':
                    Flash::error('Server Error.  Please check your WSDL version is correct for the active cluster.');
                    return redirect()->back();
                    break;

                case isset($e->message->faultstring):
                    Flash::error($e->message->faultstring);
                    return redirect()->back();
                    break;

                default:
                    Flash::error('Unknown Error.  Please check system logs');
                    Log::error('Unknown Error.', [ $e ]);
                    return redirect()->back();
            }
        }

        return parent::render($request, $e);
    }
}
