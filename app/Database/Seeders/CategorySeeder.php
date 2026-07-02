<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Database\Factories\CategoryFactory;

final class CategorySeeder extends AbstractSeeder
{
    private const CATEGORIES = [
        ['title' => 'Технологии', 'slug' => 'tehnologii'],
        ['title' => 'Искусственный интеллект', 'slug' => 'iskusstvennyy-intellekt'],
        ['title' => 'Разработка', 'slug' => 'razrabotka'],
        ['title' => 'Дизайн', 'slug' => 'dizayn'],
        ['title' => 'Бизнес', 'slug' => 'biznes'],
        ['title' => 'Стартапы', 'slug' => 'startapy'],
        ['title' => 'Маркетинг', 'slug' => 'marketing'],
        ['title' => 'Карьера', 'slug' => 'karera'],
    ];

    public function run(): void
    {
        $repository = $this->container->get(CategoryRepositoryInterface::class);
        $factory = $this->container->make(CategoryFactory::class);

        foreach (self::CATEGORIES as $category) {
            $data = $factory->make(
                title: $category['title'],
                slug: $category['slug'],
            );

            $repository->create($data);
        }

        echo 'Seeded categories: ' . count(self::CATEGORIES) . PHP_EOL;
    }
}
