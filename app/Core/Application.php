<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Core\Router\Router;
use App\View\View;
use Dotenv\Dotenv;

final class Application
{
    private readonly Container $container;

    public function __construct()
    {
        $this->container = new Container();
        $this->registerBindings();
    }

    public function run(): void
    {
        $request = $this->container->get(Request::class);
        $router = $this->container->get(Router::class);

        $routes = require base_path('config/routes.php');
        $routes($router);

        $response = $router->dispatch($request);
        $response->send();
    }

    private function registerBindings(): void
    {
        $this->loadEnvironment();

        $this->container->singleton(Container::class, fn (): Container => $this->container);
        $this->container->singleton(Request::class, fn (): Request => Request::capture());
        $this->container->singleton(Response::class, fn (): Response => new Response());
        $this->container->singleton(View::class, fn (): View => View::create());
        $this->container->singleton(Router::class, fn (): Router => new Router($this->container));
    }

    private function loadEnvironment(): void
    {
        $envPath = base_path();

        if (is_file($envPath . '/.env')) {
            Dotenv::createImmutable($envPath)->safeLoad();
        } elseif (is_file($envPath . '/.env.example')) {
            Dotenv::createImmutable($envPath, '.env.example')->safeLoad();
        }
    }

    public function container(): Container
    {
        return $this->container;
    }
}
