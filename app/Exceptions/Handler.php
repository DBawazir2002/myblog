<?php

namespace App\Exceptions;

use App\Traits\ResponseHelper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;
use Twilio\Exceptions\RestException;


class Handler extends ExceptionHandler
{
    use ResponseHelper;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof RestException) {
            return $this->error('verification code is expired',401);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->error('Not found', 404);
        }
        
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->error('Error occur in methods', 500);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->error('Record not found', 404);
        }
        
          if ($exception instanceof Throwable) {
            return $this->error($exception->getMessage(), 400);
         }
        return parent::render($request, $exception);
    }
}
