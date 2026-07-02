<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Core\Container;
use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Core\Router\Router;
use App\Database\ConnectionFactory;
use App\Database\Database;
use App\Database\DatabaseConfig;
use App\Http\ExceptionHandler;
use App\Http\Kernel;
use App\Http\Middleware\MiddlewarePipeline;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\Hydrators\RowHydrator;
use App\Database\Factories\ArticleFactory;
use App\Database\Factories\CategoryFactory;
use App\Database\Seeders\DatabaseSeeder;
use App\Services\ArticleService;
use App\Services\CategoryService;
use App\Services\HomeService;
use App\Services\SearchService;
use App\Support\SlugGenerator;
use App\View\View;
use Faker\Factory as FakerFactory;
use Faker\Generator;

final class AppServiceProvider
{
    public function register(Container $container): void
    {
        $container->singleton(Container::class, fn (): Container => $container);
        $container->singleton(Request::class, fn (): Request => Request::capture());
        $container->singleton(View::class, fn (): View => View::create());
        $container->singleton(Response::class, fn (Container $c): Response => new Response($c->get(View::class)));

        $container->singleton(DatabaseConfig::class, fn (): DatabaseConfig => DatabaseConfig::fromConfig());
        $container->singleton(ConnectionFactory::class, fn (Container $c): ConnectionFactory => new ConnectionFactory($c->get(DatabaseConfig::class)));
        $container->singleton(Database::class, fn (Container $c): Database => new Database($c->get(ConnectionFactory::class)->createWithDatabase()));
        $container->singleton(RowHydrator::class, fn (): RowHydrator => new RowHydrator());
        $container->singleton(ArticleRepositoryInterface::class, fn (Container $c): ArticleRepositoryInterface => $c->make(ArticleRepository::class));
        $container->singleton(CategoryRepositoryInterface::class, fn (Container $c): CategoryRepositoryInterface => $c->make(CategoryRepository::class));

        $container->singleton(Generator::class, fn (): Generator => FakerFactory::create('ru_RU'));
        $container->singleton(SlugGenerator::class, fn (): SlugGenerator => new SlugGenerator());
        $container->singleton(CategoryFactory::class, fn (Container $c): CategoryFactory => $c->make(CategoryFactory::class));
        $container->singleton(ArticleFactory::class, fn (Container $c): ArticleFactory => $c->make(ArticleFactory::class));
        $container->singleton(DatabaseSeeder::class, fn (Container $c): DatabaseSeeder => $c->make(DatabaseSeeder::class));

        $container->singleton(HomeService::class, fn (Container $c): HomeService => $c->make(HomeService::class));
        $container->singleton(CategoryService::class, fn (Container $c): CategoryService => $c->make(CategoryService::class));
        $container->singleton(ArticleService::class, fn (Container $c): ArticleService => $c->make(ArticleService::class));
        $container->singleton(SearchService::class, fn (Container $c): SearchService => $c->make(SearchService::class));

        $container->singleton(Router::class, fn (Container $c): Router => new Router($c));
        $container->singleton(MiddlewarePipeline::class, fn (Container $c): MiddlewarePipeline => new MiddlewarePipeline(
            $c,
            config('http.middleware', []),
        ));
        $container->singleton(ExceptionHandler::class, fn (Container $c): ExceptionHandler => $c->make(ExceptionHandler::class));
        $container->singleton(Kernel::class, fn (Container $c): Kernel => $c->make(Kernel::class));

        $container->alias('kernel', Kernel::class);
        $container->alias('router', Router::class);
    }
}
