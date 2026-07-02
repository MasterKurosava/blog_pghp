<?php

declare(strict_types=1);

namespace App\Core\Request;

final class Request
{
    public function __construct(
        private readonly array $query,
        private readonly array $request,
        private readonly array $files,
        private readonly array $server,
        private readonly string $uri,
        private readonly string $method,
    ) {
    }

    public static function capture(): self
    {
        $uri = normalize_uri($_SERVER['REQUEST_URI'] ?? '/');
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        return new self(
            query: $_GET,
            request: $_POST,
            files: $_FILES,
            server: $_SERVER,
            uri: $uri,
            method: $method,
        );
    }

    public function withQuery(array $query): self
    {
        return new self(
            query: $query,
            request: $this->request,
            files: $this->files,
            server: $this->server,
            uri: $this->uri,
            method: $this->method,
        );
    }

    public function withPost(array $request): self
    {
        return new self(
            query: $this->query,
            request: $request,
            files: $this->files,
            server: $this->server,
            uri: $this->uri,
            method: $this->method,
        );
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function isMethod(string $method): bool
    {
        return $this->method === strtoupper($method);
    }

    public function query(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->query;
        }

        return $this->query[$key] ?? $default;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->query($key, $default);
    }

    public function allGet(): array
    {
        return $this->query;
    }

    public function input(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return array_merge($this->query, $this->request);
        }

        return $this->request[$key] ?? $this->query[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->request) || array_key_exists($key, $this->query);
    }

    public function only(array $keys): array
    {
        $data = $this->input();

        return array_intersect_key($data, array_flip($keys));
    }

    public function except(array $keys): array
    {
        $data = $this->input();

        return array_diff_key($data, array_flip($keys));
    }

    public function filled(string $key): bool
    {
        $value = $this->input($key);

        return $value !== null && $value !== '';
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $this->request[$key] ?? $default;
    }

    public function allPost(): array
    {
        return $this->request;
    }

    public function file(string $key): ?array
    {
        $file = $this->files[$key] ?? null;

        if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        return $file;
    }

    public function allFiles(): array
    {
        return $this->files;
    }

    public function server(string $key, mixed $default = null): mixed
    {
        return $this->server[$key] ?? $default;
    }
}
