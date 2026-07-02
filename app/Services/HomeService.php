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
            'title' => (string) config('home.meta_title', 'Главная'),
            'mainClass' => 'main--home',
            'fullWidthContent' => true,
            'hero' => config('home.hero', []),
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
                    fn (Article $article): array => $this->mapArticle($article, $item->category->title),
                    array_slice($item->articles, 0, $limit),
                ),
            ];
        }

        return $result;
    }

    private function mapArticle(Article $article, string $categoryTitle): array
    {
        return [
            'title' => $article->title,
            'slug' => $article->slug,
            'url' => url('article/' . $article->slug),
            'description' => $article->description ?? '',
            'image' => $article->image ?? '',
            'published_at' => format_date_ru($article->publishedAt),
            'views' => format_views($article->views),
            'category' => $categoryTitle,
        ];
    }
}
