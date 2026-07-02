<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\DTO\CategoryWithArticles;
use App\Models\Category;

final class CategoryRepository extends AbstractRepository implements CategoryRepositoryInterface
{
    public function findAll(): array
    {
        $statement = $this->pdo()->query(
            'SELECT ' . self::CATEGORY_COLUMNS . '
             FROM categories c
             ORDER BY c.title ASC'
        );

        $rows = $statement->fetchAll();

        return array_map(
            fn (array $row): Category => $this->hydrator->hydrateCategory($row),
            $rows,
        );
    }

    public function findById(int $id): ?Category
    {
        $statement = $this->pdo()->prepare(
            'SELECT ' . self::CATEGORY_COLUMNS . '
             FROM categories c
             WHERE c.id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->hydrator->hydrateCategory($row);
    }

    public function findBySlug(string $slug): ?Category
    {
        $statement = $this->pdo()->prepare(
            'SELECT ' . self::CATEGORY_COLUMNS . '
             FROM categories c
             WHERE c.slug = :slug
             LIMIT 1'
        );
        $statement->execute(['slug' => $slug]);

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->hydrator->hydrateCategory($row);
    }

    public function findWithArticles(): array
    {
        $statement = $this->pdo()->query(
            'SELECT
                c.id,
                c.title,
                c.description,
                c.slug,
                c.created_at,
                c.updated_at,
                a.id AS article_id,
                a.title AS article_title,
                a.slug AS article_slug,
                a.description AS article_description,
                a.content AS article_content,
                a.image AS article_image,
                a.views AS article_views,
                a.published_at AS article_published_at,
                a.created_at AS article_created_at,
                a.updated_at AS article_updated_at
             FROM categories c
             LEFT JOIN article_category ac ON ac.category_id = c.id
             LEFT JOIN articles a ON a.id = ac.article_id AND a.published_at IS NOT NULL
             ORDER BY c.title ASC, a.published_at DESC'
        );

        $rows = $statement->fetchAll();
        $grouped = [];

        foreach ($rows as $row) {
            $categoryId = (int) $row['id'];

            if (!isset($grouped[$categoryId])) {
                $grouped[$categoryId] = [
                    'category' => $this->hydrator->hydrateCategory($row),
                    'articles' => [],
                ];
            }

            if ($row['article_id'] === null) {
                continue;
            }

            $grouped[$categoryId]['articles'][] = $this->hydrator->hydrateArticleFromAlias($row);
        }

        $result = [];

        foreach ($grouped as $item) {
            $result[] = new CategoryWithArticles(
                category: $item['category'],
                articles: $item['articles'],
            );
        }

        return $result;
    }

    public function findOnlyNonEmpty(): array
    {
        $statement = $this->pdo()->query(
            'SELECT ' . self::CATEGORY_COLUMNS . '
             FROM categories c
             WHERE EXISTS (
                 SELECT 1
                 FROM article_category ac
                 WHERE ac.category_id = c.id
             )
             ORDER BY c.title ASC'
        );

        $rows = $statement->fetchAll();

        return array_map(
            fn (array $row): Category => $this->hydrator->hydrateCategory($row),
            $rows,
        );
    }
}
