<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;

final class DatabaseSeeder extends AbstractSeeder
{
    public function run(): void
    {
        $articles = $this->container->get(ArticleRepositoryInterface::class);
        $categories = $this->container->get(CategoryRepositoryInterface::class);

        echo 'Clearing existing data...' . PHP_EOL;
        $articles->clearAll();
        $categories->clearAll();

        $this->container->make(CategorySeeder::class)->run();
        $this->container->make(ArticleSeeder::class)->run();

        echo 'Database seeding completed.' . PHP_EOL;
    }
}
