<?php 

namespace App\Controllers;

use App\Models\Article;
use App\Views\FrontView;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FrontController
{
    private Article $article_model;
    private FrontView $front_view;

    public function __construct( Article $article, FrontView $frontview)
    {
        $this->article_model = $article;
        $this->front_view = $frontview;

    }

    public function responseWrapper(string $str):ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($str);
        return $response;

    }
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $articles = $this->article_model->all();
        $html = $this->front_view->articleList($articles);
        return $this->responseWrapper($html);
    }
}