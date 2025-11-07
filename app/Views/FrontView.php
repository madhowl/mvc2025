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

    public function articleList($articles): string
    {
        return $this->twig->render('articles-list.twig',['articles' => $articles]);
    }
}