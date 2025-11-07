<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

$container = new League\Container\Container();

// Frontend template init
$container->add('TwigLoader',\Twig\Loader\FilesystemLoader::class)
    ->addArgument(TEMPLATES_PATH);

$container->add('Twig',\Twig\Environment::class)
    ->addArgument($container->get('TwigLoader'))
    ->addArgument(['debug' => true]);

$container->add(\App\Views\FrontView::class)
    ->addArgument(
        $container->get('Twig')
    );
$container->add(\App\Models\Article::class);

$container->add( \App\Controllers\FrontdController::class)
    ->addArgument(
        $container->get(\App\Models\Article::class)
    )
    ->addArgument($container->get(\App\Views\FrontView::class));

$strategy = (new League\Route\Strategy\ApplicationStrategy)->setContainer($container);
$router   = (new League\Route\Router)->setStrategy($strategy);
//$router->middleware(new AuthMiddleware);
return $router;
