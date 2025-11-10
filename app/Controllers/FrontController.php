<?php 

namespace App\Controllers;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Article;
use App\Views\FrontView;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FrontController
{
    private PostRepositoryInterface $postRepository;
    private FrontView $front_view;

    public function __construct( PostRepositoryInterface $repository, FrontView $frontview)
    {
        $this->postRepository = $repository;
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
        $posts = $this->postRepository->all();
        $html = $this->front_view->articleList($posts);
        return $this->responseWrapper($html);
    }

    public function show(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $post = $this->postRepository->find($id);
        //var_dump($post);exit();
        if (!$post) {
            return $this->responseWrapper('Post not found');
        }
        $html = $this->front_view->article($post);
        //var_dump($html);exit();
        return $this->responseWrapper($html);
    }

}