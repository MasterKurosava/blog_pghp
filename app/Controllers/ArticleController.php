<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Services\ArticleService;
use App\View\View;

final class ArticleController extends Controller
{
    public function __construct(
        View $view,
        Request $request,
        Response $response,
        private readonly ArticleService $articles,
    ) {
        parent::__construct($view, $request, $response);
    }

    public function show(string $slug): Response
    {
        return $this->render('pages/article', $this->articles->getShowPageData($slug));
    }
}
