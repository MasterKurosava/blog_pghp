<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\ArticleRepositoryInterface;

final class ArticleService
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
    ) {
    }

    public function getShowPageData(string $slug): array
    {
        return [
            'title' => 'Статья',
            'heading' => 'Статья',
            'slug' => $slug,
        ];
    }
}
