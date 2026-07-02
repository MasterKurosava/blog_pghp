<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\DTO\PaginationResult;
use App\Models\Article;
use App\Models\Category;

interface ArticleRepositoryInterface
{
    public function findById(int $id): ?Article;

    public function findBySlug(string $slug): ?Article;

    public function findLatest(int $limit): array;

    public function findByCategory(int $categoryId, int $limit = 0): array;

    public function incrementViews(int $id): void;

    public function findRelated(int $articleId, int $limit): array;

    public function countByCategory(int $categoryId): int;

    public function findPaginated(int $page, int $perPage, ?int $categoryId = null): PaginationResult;

    public function findCategoriesByArticleId(int $articleId): array;
}
