<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request\Request;
use App\Core\Response\Response;
use App\View\View;

final class ErrorController extends Controller
{
    public function notFound(string $message = 'Страница не найдена'): Response
    {
        return $this->response->view('pages/errors/404', [
            'title' => $message,
        ], 404);
    }

    public function serverError(string $message = 'Внутренняя ошибка сервера'): Response
    {
        return $this->response->view('pages/errors/500', [
            'title' => 'Ошибка сервера',
            'message' => $message,
        ], 500);
    }
}
