<?php

declare(strict_types=1);

namespace App\Core;

use App\Http\Kernel;
use App\Providers\AppServiceProvider;
use App\Core\Request\Request;
use App\Core\Router\Router;

final class Application
{
    private readonly Container $container;

    public function __construct()
    {
        $this->container = new Container();
        $this->bootstrap();
    }

    public function run(): void
    {
        $request = $this->container->get(Request::class);
        $kernel = $this->container->get(Kernel::class);

        $routes = require base_path('config/routes.php');
        $routes($this->container->get('router'));

        $response = $kernel->handle($request);
        $response->send();
    }

    private function bootstrap(): void
    {
        load_environment();
        ensure_storage_directories();

        (new AppServiceProvider())->register($this->container);
    }

    public function container(): Container
    {
        return $this->container;
    }
}
