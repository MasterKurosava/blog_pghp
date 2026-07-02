<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

final class Database
{
    public function __construct(
        private readonly PDO $pdo,
    ) {
    }

    public function connection(): PDO
    {
        return $this->pdo;
    }
}
