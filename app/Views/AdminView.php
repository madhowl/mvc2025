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

}