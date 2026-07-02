<?php

declare(strict_types=1);

namespace App\Core\Router;

final class Route
{
    public function __construct(
        public readonly string $method,
        public readonly string $pattern,
        public readonly array $handler,
        public readonly string $regex,
        public readonly array $parameterNames,
    ) {
    }
}
