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
        $content = $this->view->render($template, $data, $layout);

        return $this->response
            ->status(200)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->setContent($content);
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
