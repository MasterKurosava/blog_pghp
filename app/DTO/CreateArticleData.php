<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class CreateArticleData
{
    public function __construct(
        public string $title,
        public string $slug,
        public string $description,
        public string $content,
        public string $image,
        public int $views,
        public string $publishedAt,
    ) {
    }
}
