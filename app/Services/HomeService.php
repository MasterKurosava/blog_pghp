<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Article;

final class HomeService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories,
    ) {
    }

    public function getIndexPageData(): array
    {
        return [
            'title' => str('home.meta_title'),
            'mainClass' => 'main--home',
            'fullWidthContent' => true,
            'hero' => [
                'badge' => str('hero.badge'),
                'title' => str('hero.title'),
                'subtitle' => str('hero.subtitle'),
                'cta' => str('hero.cta'),
                'cta_href' => '#categories',
            ],
            'categories' => $this->buildCategories(),
        ];
    }

    private function buildCategories(): array
    {
        $limit = (int) config('home.articles_per_category', 3);
        $result = [];

        foreach ($this->categories->findWithArticles() as $item) {
            if ($item->articles === []) {
                continue;
            }

            $result[] = [
                'title' => $item->category->title,
                'slug' => $item->category->slug,
                'url' => url('category/' . $item->category->slug),
                'description' => $item->category->description ?? '',
                'articles' => array_map(
                    fn (Article $article): array => map_article_card(
                        $article,
                        $item->category->title,
                    ),
                    array_slice($item->articles, 0, $limit),
                ),
            ];
        }

        return $result;
    }
}
