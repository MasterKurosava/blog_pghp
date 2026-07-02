<?php

declare(strict_types=1);

use App\Core\Response\Response;

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
