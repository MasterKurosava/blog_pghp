<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request\Request;
use App\Core\Response\Response;
use App\View\View;

abstract class Controller
{
    public function __construct(
        protected readonly View $view,
        protected readonly Request $request,
        protected readonly Response $response,
    ) {
    }

    protected function render(string $template, array $data = [], ?string $layout = 'layouts/main'): Response
    {
        return $this->response->view($template, $data, 200, $layout);
    }

    protected function redirect(string $url, int $status = 302): Response
    {
        return $this->response->redirect($url, $status);
    }

    protected function json(array $data, int $status = 200): Response
    {
        return $this->response->json($data, $status);
    }
}
