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
