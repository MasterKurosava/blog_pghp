<?php

declare(strict_types=1);

namespace App\Http;

use App\Controllers\ErrorController;
use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Exceptions\Http\HttpException;
use App\Exceptions\Http\NotFoundException;
use App\View\View;
use Throwable;

final class ExceptionHandler
{
    public function __construct(
        private readonly ErrorController $errors,
        private readonly View $view,
        private readonly Response $response,
    ) {
    }

    public function handle(Throwable $exception, Request $request): Response
    {
        if ($exception instanceof NotFoundException) {
            return $this->errors->notFound($exception->getMessage());
        }

        if ($exception instanceof HttpException) {
            return $this->renderHttpException($exception);
        }

        if (config('app.debug')) {
            return $this->renderDebug($exception, $request);
        }

        return $this->errors->serverError();
    }

    private function renderHttpException(HttpException $exception): Response
    {
        if ($exception->statusCode() === 404) {
            return $this->errors->notFound($exception->getMessage());
        }

        if ($exception->statusCode() >= 500 && !config('app.debug')) {
            return $this->errors->serverError();
        }

        $content = $this->view->render('pages/errors/generic', [
            'title' => 'Ошибка',
            'status' => $exception->statusCode(),
            'message' => $exception->getMessage(),
        ]);

        return $this->response
            ->status($exception->statusCode())
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->setContent($content);
    }

    private function renderDebug(Throwable $exception, Request $request): Response
    {
        $content = $this->view->render('pages/errors/debug', [
            'title' => 'Отладка',
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'uri' => $request->uri(),
            'method' => $request->method(),
        ], null);

        return $this->response
            ->status(500)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->setContent($content);
    }
}
