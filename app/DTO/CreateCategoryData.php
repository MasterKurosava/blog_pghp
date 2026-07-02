<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class CreateCategoryData
{
    public function __construct(
        public string $title,
        public string $slug,
        public string $description,
    ) {
    }
}
