<?php

declare(strict_types=1);

namespace App\Http;

use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Core\Router\Router;
use App\Http\Middleware\MiddlewarePipeline;
use Throwable;

final class Kernel
{
    public function __construct(
        private readonly MiddlewarePipeline $middleware,
        private readonly Router $router,
        private readonly ExceptionHandler $exceptionHandler,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            return $this->middleware->handle(
                $request,
                fn (Request $request): Response => $this->router->dispatch($request),
            );
        } catch (Throwable $exception) {
            return $this->exceptionHandler->handle($exception, $request);
        }
    }
}
