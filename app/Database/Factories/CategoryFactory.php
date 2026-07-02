<?php

declare(strict_types=1);

namespace App\Database\Factories;

use App\DTO\CreateCategoryData;

final class CategoryFactory
{
    public function make(string $title, string $slug, ?string $description = null): CreateCategoryData
    {
        return new CreateCategoryData(
            title: $title,
            slug: $slug,
            description: $description ?? 'Материалы и аналитика по теме «' . $title . '».',
        );
    }
}
