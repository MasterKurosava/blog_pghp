<?php

declare(strict_types=1);

namespace App\Database\Factories;

use App\DTO\CreateCategoryData;
use Faker\Generator;

final class CategoryFactory
{
    public function __construct(
        private readonly Generator $faker,
    ) {
    }

    public function make(string $title, string $slug, ?string $description = null): CreateCategoryData
    {
        return new CreateCategoryData(
            title: $title,
            slug: $slug,
            description: $description ?? $this->faker->paragraph(random_int(2, 3)),
        );
    }
}
