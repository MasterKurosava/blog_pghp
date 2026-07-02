<?php

declare(strict_types=1);

namespace App\DTO;

use App\Models\Article;
use App\Models\Category;

final readonly class CategoryWithArticles
{
    public function __construct(
        public Category $category,
        public array $articles,
    ) {
    }
}
