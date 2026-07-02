<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Models\Article;

final class SearchService
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
    ) {
    }

    public function search(string $query): array
    {
        $query = trim($query);
        $minLength = (int) config('ui.search_min_length', 2);

        if (mb_strlen($query) < $minLength) {
            return [
                'query' => $query,
                'articles' => [],
                'total' => 0,
            ];
        }

        $limit = (int) config('ui.search_limit', 12);
        $articles = $this->articles->searchByTitle($query, $limit);
        $categoryMap = $this->articles->findCategoriesGroupedByArticleIds(
            array_map(static fn (Article $article): int => $article->id, $articles),
        );

        $mapped = array_map(
            static function (Article $article) use ($categoryMap): array {
                $categories = $categoryMap[$article->id] ?? [];
                $primary = $categories[0] ?? null;

                return map_article_card(
                    $article,
                    $primary?->title,
                    map_category_badges($categories),
                );
            },
            $articles,
        );

        return [
            'query' => $query,
            'articles' => $mapped,
            'total' => count($mapped),
        ];
    }
}
