<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

class DatabaseTestCase extends TestCase
{
    protected EntityManager $em;

    protected function setUp(): void
    {
        parent::setUp();

        $container = require __DIR__ . '/../../config/container.php';

        $this->em = $container->get(EntityManager::class);

        // Create schema fresh for each test
        $schemaTool = new SchemaTool($this->em);

        $metadata = $this->em->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }
}
