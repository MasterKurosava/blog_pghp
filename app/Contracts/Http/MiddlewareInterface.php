<?php

declare(strict_types=1);

namespace App\Contracts\Http;

use App\Core\Request\Request;
use App\Core\Response\Response;
use Closure;

interface MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response;
}
