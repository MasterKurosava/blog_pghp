<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Exceptions\Http\NotFoundException;
use App\Models\Article;
use App\Models\Category;

final class CategoryService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories,
        private readonly ArticleRepositoryInterface $articles,
    ) {
    }

    public function getIndexPageData(): array
    {
        return [
            'title' => str('category.index_title'),
            'heading' => str('category.index_heading'),
        ];
    }

    public function getShowPageData(string $slug, int $page, string $sort): array
    {
        $category = $this->categories->findBySlug($slug);

        if ($category === null) {
            throw new NotFoundException('Категория не найдена');
        }

        $sort = $this->normalizeSort($sort);
        $perPage = (int) config('category.per_page', 9);
        $page = max(1, $page);

        $pagination = $this->articles->findPaginated($page, $perPage, $category->id, $sort);

        $articles = array_map(
            fn (Article $article): array => map_article_card($article, $category->title),
            $pagination->items,
        );

        $urlBuilder = static fn (int $targetPage): string => category_url($category->slug, $targetPage, $sort);

        return [
            'title' => $category->title,
            'metaDescription' => $this->buildMetaDescription($category),
            'canonical' => category_url($category->slug, $pagination->page, $sort),
            'breadcrumbs' => [
                ['label' => str('breadcrumb.home'), 'url' => url('/')],
                ['label' => $category->title],
            ],
            'category' => [
                'title' => $category->title,
                'slug' => $category->slug,
                'description' => $category->description ?? '',
            ],
            'articlesCount' => $pagination->total,
            'articles' => $articles,
            'hasArticles' => $pagination->total > 0,
            'pagination' => build_pagination($pagination->page, $pagination->lastPage(), $urlBuilder),
            'sort' => $this->buildSortOptions($category->slug, $pagination->page, $sort),
            'homeUrl' => url('/'),
            'categorySlug' => $category->slug,
            'currentSort' => $sort,
        ];
    }

    public function getArticlesApiData(string $slug, int $page, string $sort): array
    {
        $data = $this->getShowPageData($slug, $page, $sort);

        return [
            'articles' => $data['articles'],
            'pagination' => $data['pagination'],
            'page' => $page,
        ];
    }

    private function normalizeSort(string $sort): string
    {
        $allowed = array_keys(config('category.sorts', []));

        return in_array($sort, $allowed, true)
            ? $sort
            : (string) config('category.default_sort', 'newest');
    }

    private function buildMetaDescription(Category $category): string
    {
        if ($category->description !== null && $category->description !== '') {
            return $category->description;
        }

        return 'Статьи в категории «' . $category->title . '»';
    }

    private function buildSortOptions(string $slug, int $page, string $current): array
    {
        $labels = config('category.sorts', []);
        $options = [];

        foreach ($labels as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label,
                'url' => category_url($slug, $page, $value),
                'active' => $value === $current,
            ];
        }

        return [
            'current' => $current,
            'options' => $options,
        ];
    }
}
