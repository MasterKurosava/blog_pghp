<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\DTO\CreateCategoryData;
use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?Category;

    public function findBySlug(string $slug): ?Category;

    public function findWithArticles(): array;

    public function findOnlyNonEmpty(): array;

    public function create(CreateCategoryData $data): int;

    public function clearAll(): void;
}
