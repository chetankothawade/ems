<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $e) {
            $response = (new ResponseFactory())->createResponse(422);
            $response->getBody()->write(json_encode([
                'message' => 'Validation failed',
                'errors' => $e->getErrors(),
            ]));

            return $response
                ->withStatus(422)
                ->withHeader('Content-Type', 'application/json');
        }
    }
}
