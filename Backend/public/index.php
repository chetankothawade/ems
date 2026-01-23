<?php
declare(strict_types=1);

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use DI\Container;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Load .env
|--------------------------------------------------------------------------
*/
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

/*
|--------------------------------------------------------------------------
| Container
|--------------------------------------------------------------------------
*/
$container = require __DIR__ . '/../config/container.php';

AppFactory::setContainer($container);

$app = AppFactory::create();

/*
|--------------------------------------------------------------------------
| Body Parsing (JSON/Form)
|--------------------------------------------------------------------------
*/
$app->addBodyParsingMiddleware();

/*
|--------------------------------------------------------------------------
| Routing Middleware
|--------------------------------------------------------------------------
*/
$app->addRoutingMiddleware();


/*
|--------------------------------------------------------------------------
| Error Middleware
|--------------------------------------------------------------------------
| true,true,true for dev
| change to false,false,false in production
*/
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/
(require __DIR__ . '/../config/routes.php')($app);


/*
|--------------------------------------------------------------------------
| Create PSR-7 Request (Nyholm)
|--------------------------------------------------------------------------
*/
$psr17Factory = new Psr17Factory();

$creator = new ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);

$request = $creator->fromGlobals();

/*
|--------------------------------------------------------------------------
| Run App
|--------------------------------------------------------------------------
*/
$response = $app->handle($request);

(new Slim\ResponseEmitter())->emit($response);
