<?php

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require '../vendor/autoload.php';
 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

// Загружаем переменные окружения из .env файла
$dotenv = Dotenv::createImmutable(dirname(__DIR__)); // dirname(__DIR__) указывает на корень проекта
$dotenv->load();

require_once '../config/settings.php';

$router   = require (ROOT_PATH.'/app/bootstrap.php');



$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$router->map('GET', '/', 'App\Controllers\FrontController::index');
$router->get('/post/{id}', 'App\Controllers\FrontController::showPost');

$router->get('/login', 'App\Controllers\AuthController::showLoginForm');
$router->post('/login', 'App\Controllers\AuthController::showLoginForm');

$router->map('GET', '/admin', 'App\Controllers\AdminController::index');


$response = $router->dispatch($request);

// send the response to the browser
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);

