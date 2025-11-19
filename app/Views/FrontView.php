<?php

namespace App\Views;

use Twig\Environment;

class FrontView
{

    public Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function homePage($posts): string
    {
        return $this->twig->render('front/pages/category.html.twig',['posts' => $posts]);
    }
    public function article($post): string
    {
        return $this->twig->render('front/pages/single-page.html.twig',['post' => $post]);
    }
    public function error404(): string
    {
        return $this->twig->render('front/pages/page-404.html.twig');
    }
}