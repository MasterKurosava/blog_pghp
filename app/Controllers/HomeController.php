<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Services\HomeService;
use App\View\View;

final class HomeController extends Controller
{
    public function __construct(
        View $view,
        Request $request,
        Response $response,
        private readonly HomeService $home,
    ) {
        parent::__construct($view, $request, $response);
    }

    public function index(): Response
    {
        return $this->render('pages/home', $this->home->getIndexPageData());
    }
}
