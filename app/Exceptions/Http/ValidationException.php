<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

final class ValidationException extends HttpException
{
    public function __construct(
        private readonly array $errors,
        string $message = 'Ошибка валидации',
    ) {
        parent::__construct(422, $message);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
