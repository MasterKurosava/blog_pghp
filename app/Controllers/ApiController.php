<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Services\CategoryService;
use App\Services\SearchService;
use App\View\View;

final class ApiController extends Controller
{
    public function __construct(
        View $view,
        Request $request,
        Response $response,
        private readonly SearchService $search,
        private readonly CategoryService $categories,
    ) {
        parent::__construct($view, $request, $response);
    }

    public function search(): Response
    {
        $query = (string) $this->request->query('q', '');
        $result = $this->search->search($query);

        $result['html'] = $result['articles'] === []
            ? ''
            : $this->view->render('partials/articles-grid', [
                'articles' => $result['articles'],
            ], null);

        return $this->json($result);
    }

    public function categoryArticles(string $slug): Response
    {
        $page = max(1, (int) $this->request->query('page', 1));
        $sort = (string) $this->request->query('sort', (string) config('category.default_sort', 'newest'));

        $data = $this->categories->getArticlesApiData($slug, $page, $sort);

        return $this->json([
            'html' => $this->view->render('partials/articles-grid', [
                'articles' => $data['articles'],
            ], null),
            'page' => $data['page'],
            'lastPage' => $data['lastPage'],
            'hasMore' => $data['hasMore'],
        ]);
    }
}
