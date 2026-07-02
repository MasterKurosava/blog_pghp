<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

final class Migrator
{
    public function __construct(
        private readonly ConnectionFactory $connectionFactory,
    ) {
    }

    public function run(): void
    {
        $pdo = $this->connectionFactory->createWithoutDatabase();
        $database = $this->escapeIdentifier(config('database.database'));

        $pdo->exec("CREATE DATABASE IF NOT EXISTS {$database} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE {$database}");

        $this->ensureMigrationsTable($pdo);

        $files = glob(base_path('database/migrations/*.sql')) ?: [];
        sort($files, SORT_NATURAL);

        foreach ($files as $file) {
            $name = basename($file);

            if ($this->isApplied($pdo, $name)) {
                continue;
            }

            $sql = (string) file_get_contents($file);
            $pdo->exec($sql);
            $this->markApplied($pdo, $name);

            echo 'Applied: ' . $name . PHP_EOL;
        }
    }

    private function ensureMigrationsTable(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS migrations (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uk_migrations_migration (migration)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
    }

    private function isApplied(PDO $pdo, string $migration): bool
    {
        $statement = $pdo->prepare(
            'SELECT 1 FROM migrations WHERE migration = :migration LIMIT 1'
        );
        $statement->execute(['migration' => $migration]);

        return $statement->fetchColumn() !== false;
    }

    private function markApplied(PDO $pdo, string $migration): void
    {
        $statement = $pdo->prepare(
            'INSERT INTO migrations (migration) VALUES (:migration)'
        );
        $statement->execute(['migration' => $migration]);
    }

    private function escapeIdentifier(string $identifier): string
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }
}
