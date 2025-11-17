<?php
declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\FrontController;
use App\Factories\PostFactory;
use App\Interfaces\PostFactoryInterface;
use App\Interfaces\PostRepositoryInterface;
use App\Repositories\JsonPostRepository;
use App\Views\AdminView;
use App\Views\FrontView;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


$container = new League\Container\Container();

// Twig Environment
$container->add(Environment::class, function () {
    $loader = new FilesystemLoader(TEMPLATES_PATH);
    return new Environment($loader);
});

$container->add(FrontView::class)
    ->addArguments([Environment::class]);
$container->add(AdminView::class)
    ->addArguments([Environment::class]);
// PostFactory
$container->add(PostFactoryInterface::class, PostFactory::class);

$container->add(PostRepositoryInterface::class, JsonPostRepository::class)
    ->addArguments([PostFactoryInterface::class]);

$container->add(AdminController::class)
    ->addArgument($container->get(PostRepositoryInterface::class))
    ->addArgument($container->get(AdminView::class));

$container->add(FrontController::class)
    ->addArgument($container->get(PostRepositoryInterface::class))
    ->addArgument($container->get(FrontView::class));

$strategy = (new ApplicationStrategy)->setContainer($container);
$router = (new Router)->setStrategy($strategy);

//$router->middleware(new AuthMiddleware);

return $router;
