<?php

declare(strict_types=1);

namespace App\Database;

use App\Exceptions\DatabaseConnectionException;
use PDO;
use PDOException;

final class ConnectionFactory
{
    public function __construct(
        private readonly DatabaseConfig $config,
    ) {
    }

    public function createWithDatabase(): PDO
    {
        return $this->create($this->config->database);
    }

    public function createWithoutDatabase(): PDO
    {
        return $this->create(null);
    }

    private function create(?string $database): PDO
    {
        $dsn = $database === null
            ? sprintf(
                'mysql:host=%s;port=%d;charset=%s',
                $this->config->host,
                $this->config->port,
                $this->config->charset,
            )
            : sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $this->config->host,
                $this->config->port,
                $database,
                $this->config->charset,
            );

        try {
            return new PDO($dsn, $this->config->username, $this->config->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            throw new DatabaseConnectionException('Database connection failed.', 0, $exception);
        }
    }
}
