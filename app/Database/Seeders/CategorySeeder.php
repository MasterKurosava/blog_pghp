<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Database\Factories\CategoryFactory;

final class CategorySeeder extends AbstractSeeder
{
    public function run(): void
    {
        $repository = $this->container->get(CategoryRepositoryInterface::class);
        $factory = $this->container->make(CategoryFactory::class);
        $categories = config('categories.items', []);

        foreach ($categories as $category) {
            $data = $factory->make(
                title: (string) $category['title'],
                slug: (string) $category['slug'],
                description: (string) ($category['description'] ?? ''),
            );

            $repository->create($data);
        }

        echo 'Seeded categories: ' . count($categories) . PHP_EOL;
    }
}
