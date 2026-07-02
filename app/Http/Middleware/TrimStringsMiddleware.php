<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Contracts\Http\MiddlewareInterface;
use App\Core\Request\Request;
use App\Core\Response\Response;
use Closure;

final class TrimStringsMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request->withQuery($this->trim($request->allGet()))
            ->withPost($this->trim($request->allPost())));
    }

    private function trim(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $result[$key] = is_string($value) ? trim($value) : $value;
        }

        return $result;
    }
}
