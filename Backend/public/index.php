<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use DI\Container;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use App\Http\Middleware\ValidationExceptionMiddleware;

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
| Validation Exception Middleware
|--------------------------------------------------------------------------
*/
$app->add(new ValidationExceptionMiddleware());

/*
|--------------------------------------------------------------------------
| CORS Middleware
|--------------------------------------------------------------------------
*/
$app->add(function ($request, $handler) use ($app) {
    // Handle CORS preflight requests
    if ($request->getMethod() === 'OPTIONS') {
        $response = $app->getResponseFactory()->createResponse();
    } else {
        $response = $handler->handle($request);
    }

    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

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

// Custom error handler to return JSON
$errorMiddleware->setDefaultErrorHandler(function ($request, $exception, bool $displayErrorDetails) use ($app) {
    $response = $app->getResponseFactory()->createResponse();

    $status = 500;
    if ($exception instanceof \Slim\Exception\HttpException) {
        $status = $exception->getCode();
    }

    $data = ['message' => $exception->getMessage()];

    $response->getBody()->write(json_encode($data));

    return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
});

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
