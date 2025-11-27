<?php

namespace App\Views;

use Twig\Environment;

class AdminView
{

    public Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function showDashboard(): string
    {
        return $this->twig->render('back/pages/dashboard.html.twig');
    }

    public function showPosts($posts): string
    {
      return $this->twig->render('back/posts/index.html.twig', ['posts' =>$posts]);
    }
    public function showForm($post = []): string
    {
      return $this->twig->render('back/posts/form.html.twig', [$post]);
    }

}