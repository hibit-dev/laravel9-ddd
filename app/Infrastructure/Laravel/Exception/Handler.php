<?php

namespace App\Infrastructure\Laravel\Exception;

use App\Domain\Shared\Exception\DomainException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e): Response
    {
        if ($request->is('api/*')) {
            $httpCode = $e instanceof ValidationException ? Response::HTTP_BAD_REQUEST : $e->getStatusCode();

            return response()->json([
                'date' => date('Y-m-d H:i:s'),
            ], $httpCode);
        }

        if ($e instanceof TokenMismatchException) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['Oops! Seems you could not submit form for a long time.']);
        }

        if ($e instanceof DomainException) {
            return redirect()->back()
                ->withInput()
                ->withErrors([$e->getMessage()]);
        }

        if ($e instanceof ThrottleRequestsException) {
            if ($request->method() !== Request::METHOD_GET) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['Too many requests. Please try again later.']);
            }
        }

        return parent::render($request, $e);
    }
}
