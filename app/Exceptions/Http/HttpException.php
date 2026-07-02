<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

use Exception;

class HttpException extends Exception
{
    public function __construct(
        private readonly int $statusCode,
        string $message = '',
        int $code = 0,
        ?Exception $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }
}
