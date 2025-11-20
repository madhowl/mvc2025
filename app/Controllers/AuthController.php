<?php

namespace App\Controllers;

use App\Views\AuthView;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController
{
    private AuthView $authView;

    public function __construct(AuthView $authView)
    {
        $this->authView = $authView;
    }

    public function responseWrapper(string $str):ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($str);
        return $response;

    }

    public function showLoginForm(ServerRequestInterface $request):ResponseInterface
    {
      $html = $this->authView->showLoginFortm();
      return $this->responseWrapper($html);
    }

    public function login()
    {

    }

}