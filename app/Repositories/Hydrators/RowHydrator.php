<?php

declare(strict_types=1);

namespace App\Repositories\Hydrators;

use App\Models\Article;
use App\Models\Category;

final class RowHydrator
{
    public function hydrateCategory(array $row): Category
    {
        return new Category(
            id: (int) $row['id'],
            title: $row['title'],
            description: $row['description'],
            slug: $row['slug'],
            createdAt: $row['created_at'],
            updatedAt: $row['updated_at'],
        );
    }

    public function hydrateArticle(array $row): Article
    {
        return new Article(
            id: (int) $row['id'],
            title: $row['title'],
            slug: $row['slug'],
            description: $row['description'],
            content: $row['content'],
            image: $row['image'],
            views: (int) $row['views'],
            publishedAt: $row['published_at'],
            createdAt: $row['created_at'],
            updatedAt: $row['updated_at'],
        );
    }

    public function hydrateArticleFromAlias(array $row): Article
    {
        return new Article(
            id: (int) $row['article_id'],
            title: $row['article_title'],
            slug: $row['article_slug'],
            description: $row['article_description'],
            content: $row['article_content'],
            image: $row['article_image'],
            views: (int) $row['article_views'],
            publishedAt: $row['article_published_at'],
            createdAt: $row['article_created_at'],
            updatedAt: $row['article_updated_at'],
        );
    }
}
