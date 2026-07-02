<?php

declare(strict_types=1);

namespace App\Database;

final readonly class DatabaseConfig
{
    public function __construct(
        public string $host,
        public int $port,
        public string $database,
        public string $username,
        public string $password,
        public string $charset,
    ) {
    }

    public static function fromConfig(): self
    {
        return new self(
            host: (string) config('database.host'),
            port: (int) config('database.port'),
            database: (string) config('database.database'),
            username: (string) config('database.username'),
            password: (string) config('database.password'),
            charset: (string) config('database.charset'),
        );
    }
}
