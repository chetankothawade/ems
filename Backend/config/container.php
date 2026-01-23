<?php

declare(strict_types=1);

use DI\Container;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;

use App\Support\Clock\ClockInterface;
use App\Support\Clock\SystemClock;

$container = new Container();


$container->set(EntityManager::class, function () {

    $config = ORMSetup::createAttributeMetadataConfiguration(
        paths: [__DIR__ . '/../src/Models'],
        isDevMode: true
    );


    $connectionParams = [
        'driver'   => 'pdo_mysql',
        'host'     => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port'     => $_ENV['DB_PORT'] ?? 3306,
        'dbname'   => $_ENV['DB_NAME'] ?? 'ems',
        'user'     => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset'  => 'utf8mb4',
    ];

    $connection = DriverManager::getConnection($connectionParams, $config);

    return new EntityManager($connection, $config);
});


/* interface bindings */
$container->set(EntityManagerInterface::class, fn($c) => $c->get(EntityManager::class));
$container->set(ClockInterface::class, fn() => new SystemClock()); // ⭐ fixed


return $container;