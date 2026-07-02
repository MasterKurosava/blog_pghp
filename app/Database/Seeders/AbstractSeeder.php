<?php

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Contracts\Seeders\SeederInterface;
use App\Core\Container;

abstract class AbstractSeeder implements SeederInterface
{
    public function __construct(
        protected readonly Container $container,
    ) {
    }
}
