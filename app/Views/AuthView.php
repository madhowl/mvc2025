<?php

namespace App\Views;

use Twig\Environment;

class AuthView
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function showLoginFortm()
    {
        return $this->twig->render('/back/auth/login.html.twig');

     }

}