<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\DTO\CreateArticleData;
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
               AND a.id != :exclude_article_id
               AND a.published_at IS NOT NULL
             ORDER BY a.published_at DESC, a.id DESC
             LIMIT :limit'
        );
        $statement->bindValue('article_id', $articleId, PDO::PARAM_INT);
        $statement->bindValue('exclude_article_id', $articleId, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        $rows = $statement->fetchAll();

        return array_map(
            fn (array $row): Article => $this->hydrator->hydrateArticle($row),
            $rows,
        );
    }

    public function searchByTitle(string $query, int $limit): array
    {
        $limit = max(1, $limit);
        $statement = $this->pdo()->prepare(
            'SELECT ' . self::ARTICLE_COLUMNS . '
             FROM articles a
             WHERE a.published_at IS NOT NULL
               AND a.title LIKE :query
             ORDER BY a.published_at DESC, a.id DESC
             LIMIT :limit'
        );
        $statement->bindValue('query', '%' . $query . '%');
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        $rows = $statement->fetchAll();

        return array_map(
            fn (array $row): Article => $this->hydrator->hydrateArticle($row),
            $rows,
        );
    }

    public function findRandomExcluding(array $excludeIds, int $limit): array
    {
        if ($limit <= 0) {
            return [];
        }

        $excludeIds = array_values(array_unique(array_map('intval', $excludeIds)));
        $excludeClause = '';
        $params = [];

        if ($excludeIds !== []) {
            $placeholders = implode(',', array_fill(0, count($excludeIds), '?'));
            $excludeClause = 'AND a.id NOT IN (' . $placeholders . ')';
            $params = $excludeIds;
        }

        $statement = $this->pdo()->prepare(
            'SELECT ' . self::ARTICLE_COLUMNS . '
             FROM articles a
             WHERE a.published_at IS NOT NULL
             ' . $excludeClause . '
             ORDER BY RAND()
             LIMIT :limit'
        );

        foreach ($params as $index => $id) {
            $statement->bindValue($index + 1, $id, PDO::PARAM_INT);
        }

        $statement->bindValue(count($params) + 1, $limit, PDO::PARAM_INT);
        $statement->execute();

        $rows = $statement->fetchAll();

        return array_map(
            fn (array $row): Article => $this->hydrator->hydrateArticle($row),
            $rows,
        );
    }

    public function findCategoriesGroupedByArticleIds(array $articleIds): array
    {
        $articleIds = array_values(array_unique(array_map('intval', $articleIds)));

        if ($articleIds === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($articleIds), '?'));
        $statement = $this->pdo()->prepare(
            'SELECT ac.article_id, ' . self::CATEGORY_COLUMNS . '
             FROM categories c
             INNER JOIN article_category ac ON ac.category_id = c.id
             WHERE ac.article_id IN (' . $placeholders . ')
             ORDER BY ac.article_id ASC, c.title ASC'
        );
        $statement->execute($articleIds);

        $grouped = [];

        foreach ($statement->fetchAll() as $row) {
            $articleId = (int) $row['article_id'];
            $grouped[$articleId][] = $this->hydrator->hydrateCategory($row);
        }

        return $grouped;
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

    public function findPaginated(int $page, int $perPage, ?int $categoryId = null, string $sort = 'newest'): PaginationResult
    {
        $perPage = max(1, $perPage);
        $sort = $this->resolveSort($sort);

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
        $orderBy = $this->resolveOrderBy($sort);

        $countStatement = $this->pdo()->prepare(
            "SELECT COUNT(DISTINCT a.id)
             FROM articles a
             {$join}
             WHERE {$where}"
        );
        $countStatement->execute($params);
        $total = (int) $countStatement->fetchColumn();

        $lastPage = $total > 0 ? (int) ceil($total / $perPage) : 1;
        $page = min(max(1, $page), $lastPage);
        $offset = ($page - 1) * $perPage;

        $dataStatement = $this->pdo()->prepare(
            "SELECT DISTINCT " . self::ARTICLE_COLUMNS . "
             FROM articles a
             {$join}
             WHERE {$where}
             ORDER BY {$orderBy}
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

    private function resolveSort(string $sort): string
    {
        return match ($sort) {
            'oldest', 'views' => $sort,
            default => 'newest',
        };
    }

    private function resolveOrderBy(string $sort): string
    {
        return match ($sort) {
            'oldest' => 'a.published_at ASC, a.id ASC',
            'views' => 'a.views DESC, a.id DESC',
            default => 'a.published_at DESC, a.id DESC',
        };
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

    public function create(CreateArticleData $data): int
    {
        $statement = $this->pdo()->prepare(
            'INSERT INTO articles (title, slug, description, content, image, views, published_at)
             VALUES (:title, :slug, :description, :content, :image, :views, :published_at)'
        );
        $statement->execute([
            'title' => $data->title,
            'slug' => $data->slug,
            'description' => $data->description,
            'content' => $data->content,
            'image' => $data->image,
            'views' => $data->views,
            'published_at' => $data->publishedAt,
        ]);

        return (int) $this->pdo()->lastInsertId();
    }

    public function attachCategories(int $articleId, array $categoryIds): void
    {
        if ($categoryIds === []) {
            return;
        }

        $statement = $this->pdo()->prepare(
            'INSERT INTO article_category (article_id, category_id)
             VALUES (:article_id, :category_id)'
        );

        foreach ($categoryIds as $categoryId) {
            $statement->execute([
                'article_id' => $articleId,
                'category_id' => $categoryId,
            ]);
        }
    }

    public function clearAll(): void
    {
        $this->pdo()->exec('DELETE FROM article_category');
        $this->pdo()->exec('DELETE FROM articles');
    }
}
