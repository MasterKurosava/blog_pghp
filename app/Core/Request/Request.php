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

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    public function allGet(): array
    {
        return $this->query;
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
