<?php

declare(strict_types=1);

namespace App\Services;

final class HomeService
{
    public function getIndexPageData(): array
    {
        return [
            'title' => 'Главная',
            'heading' => 'Добро пожаловать',
        ];
    }
}
