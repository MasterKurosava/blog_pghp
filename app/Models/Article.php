<?php

declare(strict_types=1);

namespace App\Models;

final readonly class Article
{
    public function __construct(
        public int $id,
        public string $title,
        public string $slug,
        public ?string $description,
        public string $content,
        public ?string $image,
        public int $views,
        public ?string $publishedAt,
        public string $createdAt,
        public string $updatedAt,
    ) {
    }
}
