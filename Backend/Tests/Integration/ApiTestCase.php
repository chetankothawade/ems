<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

abstract class ApiTestCase extends TestCase
{
    protected App $app;
    protected EntityManager $em;
    private Psr17Factory $psr17Factory;

    protected function setUp(): void
    {
        parent::setUp();

        // Load container and get EntityManager
        $container = require __DIR__ . '/../../config/container.php';
        $this->em = $container->get(EntityManager::class);

        // Create schema fresh for each test
        $schemaTool = new SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        // Create Slim app
        AppFactory::setContainer($container);
        $this->app = AppFactory::create();

        // Add middleware
        $this->app->addBodyParsingMiddleware();
        $this->app->addRoutingMiddleware();
        $this->app->addErrorMiddleware(true, true, true);

        // Load routes
        (require __DIR__ . '/../../config/routes.php')($this->app);

        $this->psr17Factory = new Psr17Factory();
    }

    protected function createRequest(
        string $method,
        string $uri,
        array $headers = [],
        ?string $body = null
    ): ServerRequestInterface {
        $request = $this->psr17Factory->createServerRequest($method, $uri);

        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        if ($body !== null) {
            $request = $request->withBody($this->psr17Factory->createStream($body));
        }

        return $request;
    }

    protected function makeRequest(
        string $method,
        string $uri,
        array $headers = [],
        ?string $body = null
    ): ResponseInterface {
        $request = $this->createRequest($method, $uri, $headers, $body);
        return $this->app->handle($request);
    }

    protected function getJsonResponse(ResponseInterface $response): array
    {
        $body = (string) $response->getBody();
        return json_decode($body, true);
    }

    protected function assertJsonResponse(
        ResponseInterface $response,
        int $expectedStatus = 200,
        ?array $expectedData = null
    ): void {
        $this->assertEquals($expectedStatus, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        if ($expectedData !== null) {
            $actualData = $this->getJsonResponse($response);
            $this->assertEquals($expectedData, $actualData);
        }
    }
}