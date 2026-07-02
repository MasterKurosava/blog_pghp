<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

final class NotFoundException extends HttpException
{
    public function __construct(string $message = 'Страница не найдена')
    {
        parent::__construct(404, $message);
    }
}
