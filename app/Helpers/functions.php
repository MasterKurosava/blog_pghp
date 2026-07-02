<?php

declare(strict_types=1);

use App\Core\Response\Response;

if (!function_exists('load_environment')) {
    function load_environment(): void
    {
        $envPath = base_path();

        if (is_file($envPath . '/.env')) {
            Dotenv\Dotenv::createImmutable($envPath)->safeLoad();
        } elseif (is_file($envPath . '/.env.example')) {
            Dotenv\Dotenv::createImmutable($envPath, '.env.example')->safeLoad();
        }
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? null;

        if ($value === null) {
            return $default;
        }

        return match (strtolower((string) $value)) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'empty', '(empty)' => '',
            'null', '(null)' => null,
            default => $value,
        };
    }
}

if (!function_exists('config_path')) {
    function config_path(string $name): string
    {
        return base_path('config/' . $name . '.php');
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        static $config = [];

        [$file, $item] = array_pad(explode('.', $key, 2), 2, null);

        if ($item === null) {
            return $config[$file] ?? $default;
        }

        if (!isset($config[$file])) {
            $path = config_path($file);

            if (!is_file($path)) {
                return $default;
            }

            $config[$file] = require $path;
        }

        return $config[$file][$item] ?? $default;
    }
}

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        $base = dirname(__DIR__, 2);

        return $path !== '' ? $base . '/' . ltrim($path, '/') : $base;
    }
}

if (!function_exists('public_path')) {
    function public_path(string $path = ''): string
    {
        return base_path('public/' . ltrim($path, '/'));
    }
}

if (!function_exists('storage_path')) {
    function storage_path(string $path = ''): string
    {
        return base_path('storage/' . ltrim($path, '/'));
    }
}

if (!function_exists('ensure_storage_directories')) {
    function ensure_storage_directories(): void
    {
        $directories = [
            storage_path('cache/templates/compile'),
            storage_path('cache/templates/cache'),
            storage_path('logs'),
        ];

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                mkdir($directory, 0775, true);
            }
        }
    }
}

if (!function_exists('normalize_uri')) {
    function normalize_uri(string $requestUri): string
    {
        $uri = parse_url($requestUri, PHP_URL_PATH);

        if (!is_string($uri) || $uri === '') {
            return '/';
        }

        $uri = '/' . trim($uri, '/');

        return $uri === '/' ? '/' : rtrim($uri, '/');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $base = rtrim((string) config('app.url'), '/');
        $path = '/' . ltrim($path, '/');

        return $path === '/' ? $base : $base . $path;
    }
}

if (!function_exists('format_date_ru')) {
    function format_date_ru(?string $date): string
    {
        if ($date === null || $date === '') {
            return '';
        }

        $months = [
            1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
            5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
            9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря',
        ];

        $timestamp = strtotime($date);

        if ($timestamp === false) {
            return '';
        }

        $day = (int) date('j', $timestamp);
        $month = $months[(int) date('n', $timestamp)] ?? '';
        $year = date('Y', $timestamp);

        return "{$day} {$month} {$year}";
    }
}

if (!function_exists('format_views')) {
    function format_views(int $views): string
    {
        return number_format($views, 0, '', ' ');
    }
}

if (!function_exists('map_article_card')) {
    function map_article_card(
        \App\Models\Article $article,
        ?string $categoryTitle = null,
        array $categories = [],
    ): array {
        $badges = $categories;

        if ($badges === [] && $categoryTitle !== null && $categoryTitle !== '') {
            $badges = [['label' => $categoryTitle, 'variant' => 'category']];
        }

        return [
            'title' => $article->title,
            'slug' => $article->slug,
            'url' => article_url($article->slug),
            'description' => $article->description ?? '',
            'image' => $article->image ?? '',
            'published_at' => format_date_ru($article->publishedAt),
            'views' => format_views($article->views),
            'category' => $categoryTitle ?? ($badges[0]['label'] ?? ''),
            'categories' => $badges,
        ];
    }
}

if (!function_exists('map_category_badges')) {
    function map_category_badges(array $categories): array
    {
        return array_map(
            static fn (\App\Models\Category $category): array => [
                'label' => $category->title,
                'variant' => 'category',
            ],
            $categories,
        );
    }
}

if (!function_exists('map_category_links')) {
    function map_category_links(array $categories): array
    {
        return array_map(
            static fn (\App\Models\Category $category): array => [
                'label' => $category->title,
                'url' => category_url($category->slug),
            ],
            $categories,
        );
    }
}

if (!function_exists('article_url')) {
    function article_url(string $slug): string
    {
        return url('article/' . ltrim($slug, '/'));
    }
}

if (!function_exists('category_url')) {
    function category_url(string $slug, int $page = 1, string $sort = 'newest'): string
    {
        $path = url('category/' . ltrim($slug, '/'));
        $query = [];

        if ($page > 1) {
            $query['page'] = $page;
        }

        $defaultSort = (string) config('category.default_sort', 'newest');

        if ($sort !== $defaultSort) {
            $query['sort'] = $sort;
        }

        if ($query === []) {
            return $path;
        }

        return $path . '?' . http_build_query($query);
    }
}

if (!function_exists('pagination_range')) {
    function pagination_range(int $current, int $last): array
    {
        if ($last <= 7) {
            return range(1, $last);
        }

        $pages = [1];
        $start = max(2, $current - 1);
        $end = min($last - 1, $current + 1);

        if ($start > 2) {
            $pages[] = '...';
        }

        for ($i = $start; $i <= $end; $i++) {
            $pages[] = $i;
        }

        if ($end < $last - 1) {
            $pages[] = '...';
        }

        $pages[] = $last;

        return $pages;
    }
}

if (!function_exists('build_pagination')) {
    function build_pagination(int $current, int $last, callable $urlBuilder): array
    {
        if ($last <= 1) {
            return ['visible' => false];
        }

        $items = [];

        foreach (pagination_range($current, $last) as $page) {
            if ($page === '...') {
                $items[] = ['ellipsis' => true];
                continue;
            }

            $pageNumber = (int) $page;
            $items[] = [
                'number' => $pageNumber,
                'url' => $urlBuilder($pageNumber),
                'active' => $pageNumber === $current,
            ];
        }

        return [
            'visible' => true,
            'current' => $current,
            'last' => $last,
            'items' => $items,
            'prev' => [
                'url' => $urlBuilder(max(1, $current - 1)),
                'disabled' => $current <= 1,
            ],
            'next' => [
                'url' => $urlBuilder(min($last, $current + 1)),
                'disabled' => $current >= $last,
            ],
        ];
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('view_shared_data')) {
    function view_shared_data(): array
    {
        return [
            'name' => config('app.name'),
            'url' => config('app.url'),
            'defaultMetaDescription' => 'Современный блог о технологиях, дизайне и разработке.',
            'homeUrl' => url('/'),
        ];
    }
}

if (!function_exists('register_smarty_plugins')) {
    function register_smarty_plugins(Smarty $smarty): void
    {
        $smarty->registerPlugin('function', 'asset', static function (array $params): string {
            return asset((string) ($params['path'] ?? ''));
        });

        $smarty->registerPlugin('function', 'url', static function (array $params): string {
            return url((string) ($params['path'] ?? ''));
        });
    }
}

if (!function_exists('html_response')) {
    function html_response(Response $response, string $content, int $status = 200): Response
    {
        return $response
            ->status($status)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->setContent($content);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url, int $status = 302): never
    {
        header('Location: ' . $url, true, $status);
        exit;
    }
}
