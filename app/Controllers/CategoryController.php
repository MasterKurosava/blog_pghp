<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Services\CategoryService;
use App\View\View;

final class CategoryController extends Controller
{
    public function __construct(
        View $view,
        Request $request,
        Response $response,
        private readonly CategoryService $categories,
    ) {
        parent::__construct($view, $request, $response);
    }

    public function index(): Response
    {
        return $this->render('pages/category', $this->categories->getIndexPageData());
    }

    public function show(string $slug): Response
    {
        $page = (int) $this->request->query('page', 1);
        $sort = (string) $this->request->query('sort', 'newest');

        return $this->render('pages/category', $this->categories->getShowPageData($slug, $page, $sort));
    }
}
