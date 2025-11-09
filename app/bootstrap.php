<?php
declare(strict_types=1);

use App\Controllers\FrontController;
use App\Models\Article;
use App\Views\FrontView;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/../vendor/autoload.php';

$container = new League\Container\Container();

// Twig Environment
$container->add(Environment::class, function () {
    $loader = new FilesystemLoader(TEMPLATES_PATH);
    return new Environment($loader);
});
/* Frontend template init
$container->add('TwigLoader',\Twig\Loader\FilesystemLoader::class)
    ->addArgument(TEMPLATES_PATH);

$container->add('Twig',\Twig\Environment::class)
    ->addArgument($container->get('TwigLoader'))
    ->addArgument(['debug' => true]);

$container->add(\App\Views\FrontView::class)
    ->addArgument(
        $container->get('Twig')
    );
*/
$container->add(FrontView::class)
    ->addArguments([Environment::class]);

$container->add(Article::class);

$container->add( FrontController::class)
    ->addArgument(
        $container->get(Article::class)
    )
    ->addArgument($container->get(FrontView::class));

$strategy = (new ApplicationStrategy)->setContainer($container);
$router   = (new Router)->setStrategy($strategy);

//$router->middleware(new AuthMiddleware);

return $router;
