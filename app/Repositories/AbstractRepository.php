<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Repositories\Hydrators\RowHydrator;
use PDO;

abstract class AbstractRepository
{
    protected const CATEGORY_COLUMNS = 'c.id, c.title, c.description, c.slug, c.created_at, c.updated_at';

    protected const ARTICLE_COLUMNS = 'a.id, a.title, a.slug, a.description, a.content, a.image, a.views, a.published_at, a.created_at, a.updated_at';

    public function __construct(
        protected readonly Database $database,
        protected readonly RowHydrator $hydrator,
    ) {
    }

    protected function pdo(): PDO
    {
        return $this->database->connection();
    }
}
