<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Contracts\Http\MiddlewareInterface;
use App\Core\Container;
use App\Core\Request\Request;
use App\Core\Response\Response;
use Closure;

final class MiddlewarePipeline
{
    public function __construct(
        private readonly Container $container,
        private readonly array $middleware,
    ) {
    }

    public function handle(Request $request, Closure $destination): Response
    {
        $pipeline = array_reduce(
            array_reverse($this->middleware),
            fn (Closure $next, string $middleware): Closure => function (Request $request) use ($middleware, $next): Response {
                $instance = $this->container->make($middleware);

                if (!$instance instanceof MiddlewareInterface) {
                    return $next($request);
                }

                return $instance->handle($request, $next);
            },
            $destination,
        );

        return $pipeline($request);
    }
}
