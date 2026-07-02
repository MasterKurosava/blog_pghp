<?php

declare(strict_types=1);

use App\Database\ConnectionFactory;
use App\Database\DatabaseConfig;
use App\Database\Migrator;

require dirname(__DIR__) . '/vendor/autoload.php';

load_environment();

$config = DatabaseConfig::fromConfig();
$migrator = new Migrator(new ConnectionFactory($config));
$migrator->run();
