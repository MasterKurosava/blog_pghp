<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\DTO\PaginationResult;
use App\Models\Article;
use App\Models\Category;
use PDO;

final class ArticleRepository extends AbstractRepository implements ArticleRepositoryInterface
{
    public function findById(int $id): ?Article
    {
        $statement = $this->pdo()->prepare(
            'SELECT ' . self::ARTICLE_COLUMNS . '
             FROM articles a
             WHERE a.id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->hydrator->hydrateArticle($row);
    }

    public function findBySlug(string $slug): ?Article
    {
        $statement = $this->pdo()->prepare(
            'SELECT ' . self::ARTICLE_COLUMNS . '
             FROM articles a
             WHERE a.slug = :slug
             LIMIT 1'
        );
        $statement->execute(['slug' => $slug]);

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->hydrator->hydrateArticle($row);
    }

    public function findLatest(int $limit): array
    {
        $statement = $this->pdo()->prepare(
            'SELECT ' . self::ARTICLE_COLUMNS . '
             FROM articles a
             WHERE a.published_at IS NOT NULL
             ORDER BY a.published_at DESC, a.id DESC
             LIMIT :limit'
        );
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        $rows = $statement->fetchAll();

        return array_map(
            fn (array $row): Article => $this->hydrator->hydrateArticle($row),
            $rows,
        );
    }

    public function findByCategory(int $categoryId, int $limit = 0): array
    {
        $sql = 'SELECT ' . self::ARTICLE_COLUMNS . '
                FROM articles a
                INNER JOIN article_category ac ON ac.article_id = a.id
                WHERE ac.category_id = :category_id
                  AND a.published_at IS NOT NULL
                ORDER BY a.published_at DESC, a.id DESC';

        if ($limit > 0) {
            $sql .= ' LIMIT :limit';
        }

        $statement = $this->pdo()->prepare($sql);
        $statement->bindValue('category_id', $categoryId, PDO::PARAM_INT);

        if ($limit > 0) {
            $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        }

        $statement->execute();

        $rows = $statement->fetchAll();

        return array_map(
            fn (array $row): Article => $this->hydrator->hydrateArticle($row),
            $rows,
        );
    }

    public function incrementViews(int $id): void
    {
        $statement = $this->pdo()->prepare(
            'UPDATE articles
             SET views = views + 1
             WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
    }

    public function findRelated(int $articleId, int $limit): array
    {
        $statement = $this->pdo()->prepare(
            'SELECT DISTINCT ' . self::ARTICLE_COLUMNS . '
             FROM articles a
             INNER JOIN article_category ac ON ac.article_id = a.id
             INNER JOIN article_category related_ac ON related_ac.category_id = ac.category_id
             WHERE related_ac.article_id = :article_id
               AND a.id != :article_id
               AND a.published_at IS NOT NULL
             ORDER BY a.published_at DESC, a.id DESC
             LIMIT :limit'
        );
        $statement->bindValue('article_id', $articleId, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        $rows = $statement->fetchAll();

        return array_map(
            fn (array $row): Article => $this->hydrator->hydrateArticle($row),
            $rows,
        );
    }

    public function countByCategory(int $categoryId): int
    {
        $statement = $this->pdo()->prepare(
            'SELECT COUNT(DISTINCT a.id)
             FROM articles a
             INNER JOIN article_category ac ON ac.article_id = a.id
             WHERE ac.category_id = :category_id
               AND a.published_at IS NOT NULL'
        );
        $statement->execute(['category_id' => $categoryId]);

        return (int) $statement->fetchColumn();
    }

    public function findPaginated(int $page, int $perPage, ?int $categoryId = null): PaginationResult
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;

        $conditions = ['a.published_at IS NOT NULL'];
        $params = [];

        if ($categoryId !== null) {
            $conditions[] = 'ac.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        $where = implode(' AND ', $conditions);
        $join = $categoryId !== null
            ? 'INNER JOIN article_category ac ON ac.article_id = a.id'
            : '';

        $countStatement = $this->pdo()->prepare(
            "SELECT COUNT(DISTINCT a.id)
             FROM articles a
             {$join}
             WHERE {$where}"
        );
        $countStatement->execute($params);
        $total = (int) $countStatement->fetchColumn();

        $dataStatement = $this->pdo()->prepare(
            "SELECT DISTINCT " . self::ARTICLE_COLUMNS . "
             FROM articles a
             {$join}
             WHERE {$where}
             ORDER BY a.published_at DESC, a.id DESC
             LIMIT :limit OFFSET :offset"
        );

        foreach ($params as $key => $value) {
            $dataStatement->bindValue($key, $value, PDO::PARAM_INT);
        }

        $dataStatement->bindValue('limit', $perPage, PDO::PARAM_INT);
        $dataStatement->bindValue('offset', $offset, PDO::PARAM_INT);
        $dataStatement->execute();

        $rows = $dataStatement->fetchAll();
        $items = array_map(
            fn (array $row): Article => $this->hydrator->hydrateArticle($row),
            $rows,
        );

        return new PaginationResult(
            items: $items,
            total: $total,
            page: $page,
            perPage: $perPage,
        );
    }

    public function findCategoriesByArticleId(int $articleId): array
    {
        $statement = $this->pdo()->prepare(
            'SELECT ' . self::CATEGORY_COLUMNS . '
             FROM categories c
             INNER JOIN article_category ac ON ac.category_id = c.id
             WHERE ac.article_id = :article_id
             ORDER BY c.title ASC'
        );
        $statement->execute(['article_id' => $articleId]);

        $rows = $statement->fetchAll();

        return array_map(
            fn (array $row): Category => $this->hydrator->hydrateCategory($row),
            $rows,
        );
    }
}
