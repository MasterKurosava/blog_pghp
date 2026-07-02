<?php

declare(strict_types=1);

namespace App\Core\Response;

use App\Exceptions\Http\HttpException;
use App\View\View;

final class Response
{
    private string $content = '';
    private int $status = 200;
    private array $headers = [];

    public function __construct(
        private readonly ?View $view = null,
    ) {
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function status(int $code): self
    {
        $this->status = $code;

        return $this;
    }

    public function statusCode(): int
    {
        return $this->status;
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function view(string $template, array $data = [], int $status = 200, ?string $layout = 'layouts/main'): self
    {
        if ($this->view === null) {
            throw new HttpException(500, 'View is not configured for response.');
        }

        $content = $this->view->render($template, $data, $layout);

        return $this
            ->status($status)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->setContent($content);
    }

    public function json(array $data, int $status = 200): self
    {
        $this->status = $status;
        $this->headers['Content-Type'] = 'application/json; charset=utf-8';
        $this->content = (string) json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        return $this;
    }

    public function redirect(string $url, int $status = 302): self
    {
        $this->status = $status;
        $this->headers['Location'] = $url;
        $this->content = '';

        return $this;
    }

    public function abort(int $status, string $message = ''): never
    {
        throw new HttpException($status, $message);
    }

    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo $this->content;
    }
}
