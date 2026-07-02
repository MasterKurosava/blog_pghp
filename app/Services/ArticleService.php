<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Exceptions\Http\NotFoundException;
use App\Models\Article;
use App\Models\Category;

final class ArticleService
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
    ) {
    }

    public function getShowPageData(string $slug): array
    {
        $article = $this->articles->findBySlug($slug);

        if ($article === null || $article->publishedAt === null) {
            throw new NotFoundException('Статья не найдена');
        }

        $categories = $this->articles->findCategoriesByArticleId($article->id);

        $this->articles->incrementViews($article->id);

        $article = $this->articles->findById($article->id);

        if ($article === null) {
            throw new NotFoundException('Статья не найдена');
        }

        $primaryCategory = $categories[0] ?? null;
        $metaDescription = $this->buildMetaDescription($article);
        $relatedArticles = $this->buildRelatedArticles($article);

        return [
            'title' => $article->title,
            'metaDescription' => $metaDescription,
            'canonical' => article_url($article->slug),
            'ogTitle' => $article->title,
            'ogDescription' => $metaDescription,
            'ogImage' => $article->image ?? '',
            'breadcrumbs' => $this->buildBreadcrumbs($article, $primaryCategory),
            'article' => [
                'title' => $article->title,
                'slug' => $article->slug,
                'description' => $article->description ?? '',
                'content' => $article->content,
                'image' => $article->image ?? '',
                'published_at' => format_date_ru($article->publishedAt),
                'views' => format_views($article->views),
                'reading_time' => format_reading_time($article->content),
                'share_url' => article_url($article->slug),
                'categories' => map_category_links($categories),
            ],
            'relatedArticles' => $relatedArticles,
            'hasRelatedArticles' => $relatedArticles !== [],
            'relatedTitle' => str('article.related_title'),
        ];
    }

    private function buildMetaDescription(Article $article): string
    {
        if ($article->description !== null && $article->description !== '') {
            return $article->description;
        }

        $text = trim(strip_tags($article->content));

        if ($text === '') {
            return $article->title;
        }

        if (mb_strlen($text) <= 160) {
            return $text;
        }

        return mb_substr($text, 0, 157) . '...';
    }

    private function buildBreadcrumbs(Article $article, ?Category $primaryCategory): array
    {
        $items = [
            ['label' => str('breadcrumb.home'), 'url' => url('/')],
        ];

        if ($primaryCategory !== null) {
            $items[] = [
                'label' => $primaryCategory->title,
                'url' => category_url($primaryCategory->slug),
            ];
        }

        $items[] = ['label' => $article->title];

        return $items;
    }

    private function buildRelatedArticles(Article $article): array
    {
        $limit = (int) config('article.related_limit', 3);
        $related = $this->articles->findRelated($article->id, $limit);

        $excludeIds = [$article->id];

        foreach ($related as $relatedArticle) {
            $excludeIds[] = $relatedArticle->id;
        }

        $remaining = $limit - count($related);

        if ($remaining > 0) {
            $related = [...$related, ...$this->articles->findRandomExcluding($excludeIds, $remaining)];
        }

        if ($related === []) {
            return [];
        }

        $categoryMap = $this->articles->findCategoriesGroupedByArticleIds(
            array_map(static fn (Article $item): int => $item->id, $related),
        );

        return array_map(
            static function (Article $relatedArticle) use ($categoryMap): array {
                $categories = $categoryMap[$relatedArticle->id] ?? [];
                $primary = $categories[0] ?? null;

                return map_article_card(
                    $relatedArticle,
                    $primary?->title,
                    map_category_badges($categories),
                );
            },
            $related,
        );
    }
}
