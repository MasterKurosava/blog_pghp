<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\CategoryRepositoryInterface;

final class CategoryService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories,
    ) {
    }

    public function getIndexPageData(): array
    {
        return [
            'title' => 'Категории',
            'heading' => 'Категории',
        ];
    }

    public function getShowPageData(string $slug): array
    {
        return [
            'title' => 'Категория',
            'heading' => 'Категория',
            'slug' => $slug,
        ];
    }
}
