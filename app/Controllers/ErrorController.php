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
            'title' => 'Страница не найдена',
            'heading' => $message,
            'metaDescription' => 'Запрашиваемая страница не найдена.',
            'robots' => 'noindex, nofollow',
            'homeUrl' => url('/'),
            'mainClass' => 'main--error',
        ], 404);
    }

    public function serverError(string $message = 'Внутренняя ошибка сервера'): Response
    {
        return $this->response->view('pages/errors/500', [
            'title' => 'Ошибка сервера',
            'heading' => 'Что-то пошло не так',
            'message' => $message,
            'metaDescription' => 'На сервере произошла ошибка.',
            'robots' => 'noindex, nofollow',
            'homeUrl' => url('/'),
            'mainClass' => 'main--error',
        ], 500);
    }
}
