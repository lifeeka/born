<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        // \Illuminate\Database\Eloquent\ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     *
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if(is_subclass_of($exception,BornException::class)){
            /** @var BornException $exception */
            $exception->getCommand()->line("<fg=red>{$exception->getMessage()}</>");
            die();
        }

        parent::report($exception);
    }
}
