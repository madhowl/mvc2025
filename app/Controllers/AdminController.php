<?php

namespace App\Controllers;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Article;
use App\Views\AdminView;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AdminController
{
    private PostRepositoryInterface $postRepository;
    private mixed $adminView;

    public function __construct(PostRepositoryInterface $repository, AdminView $adminView)
    {
        $this->postRepository = $repository;
        $this->adminView = $adminView;
    }
    public function responseWrapper(string $str):ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($str);
        return $response;

    }
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $html = $this->adminView->showDashboard();
        return $this->responseWrapper($html);
    }

    public function postslist(ServerRequestInterface $request): ResponseInterface
    {
        $posts = $this->postRepository->all();
        //var_dump($posts);exit();
        $html = $this->adminView->showPosts($posts);
        return $this->responseWrapper($html);
    }
    public function postEdit(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $post = $this->postRepository->find($id);
        $html = $this->adminView->showForm($post);
        return $this->responseWrapper($html);
    }
    public function postCreate(ServerRequestInterface $request): ResponseInterface
    {
        $html = $this->adminView->showForm();
        return $this->responseWrapper($html);
    }

}