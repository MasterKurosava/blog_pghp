<?php

declare(strict_types=1);

namespace App\Core;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Database\ConnectionFactory;
use App\Database\Database;
use App\Database\DatabaseConfig;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\Hydrators\RowHydrator;
use App\View\View;
use Request\Request;
use Response\Response;
use Router\Router;

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
        load_environment();
        ensure_storage_directories();

        $this->container->singleton(Container::class, fn (): Container => $this->container);
        $this->container->singleton(Request::class, fn (): Request => Request::capture());
        $this->container->singleton(Response::class, fn (): Response => new Response());
        $this->container->singleton(DatabaseConfig::class, fn (): DatabaseConfig => DatabaseConfig::fromConfig());
        $this->container->singleton(ConnectionFactory::class, fn (Container $c): ConnectionFactory => new ConnectionFactory($c->get(DatabaseConfig::class)));
        $this->container->singleton(Database::class, fn (Container $c): Database => new Database($c->get(ConnectionFactory::class)->createWithDatabase()));
        $this->container->singleton(RowHydrator::class, fn (): RowHydrator => new RowHydrator());
        $this->container->singleton(ArticleRepositoryInterface::class, fn (Container $c): ArticleRepositoryInterface => $c->make(ArticleRepository::class));
        $this->container->singleton(CategoryRepositoryInterface::class, fn (Container $c): CategoryRepositoryInterface => $c->make(CategoryRepository::class));
        $this->container->singleton(View::class, fn (): View => View::create());
        $this->container->singleton(Router::class, fn (): Router => new Router($this->container));
    }

    public function container(): Container
    {
        return $this->container;
    }
}
