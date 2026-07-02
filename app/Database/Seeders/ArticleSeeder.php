<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Database\Factories\ArticleFactory;

final class ArticleSeeder extends AbstractSeeder
{
    public function run(): void
    {
        $articles = $this->container->get(ArticleRepositoryInterface::class);
        $categories = $this->container->get(CategoryRepositoryInterface::class);
        $factory = $this->container->make(ArticleFactory::class);

        $categoryIds = array_map(
            static fn ($category) => $category->id,
            $categories->findAll(),
        );

        if ($categoryIds === []) {
            throw new \RuntimeException('Categories must be seeded before articles.');
        }

        $articlesCount = (int) config('seeder.articles_count', 100);
        $minCategories = (int) config('seeder.categories_per_article_min', 1);
        $maxCategories = (int) config('seeder.categories_per_article_max', 3);
        $relationsCount = 0;

        for ($i = 0; $i < $articlesCount; $i++) {
            $data = $factory->make();
            $articleId = $articles->create($data);

            $selectedCategories = $this->pickCategories($categoryIds, $minCategories, $maxCategories);
            $articles->attachCategories($articleId, $selectedCategories);
            $relationsCount += count($selectedCategories);
        }

        echo 'Seeded articles: ' . $articlesCount . PHP_EOL;
        echo 'Seeded relations: ' . $relationsCount . PHP_EOL;
    }

    private function pickCategories(array $categoryIds, int $min, int $max): array
    {
        $count = random_int($min, min($max, count($categoryIds)));
        $keys = array_rand($categoryIds, $count);

        if (!is_array($keys)) {
            return [$categoryIds[$keys]];
        }

        return array_values(array_map(static fn (int $key): int => $categoryIds[$key], $keys));
    }
}
