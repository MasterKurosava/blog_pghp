<?php

declare(strict_types=1);

use App\Core\Application;
use App\Database\Seeders\DatabaseSeeder;

require dirname(__DIR__) . '/vendor/autoload.php';

$application = new Application();
$seeder = $application->container()->make(DatabaseSeeder::class);
$seeder->run();
