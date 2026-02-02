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
        'host'     => getenv('DB_HOST') ?: '127.0.0.1',
        'port'     => getenv('DB_PORT') ?: 3306,
        'dbname'   => getenv('DB_NAME') ?: 'ems',
        'user'     => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'charset'  => 'utf8mb4',
         //'use_savepoints' => false,
    ];


    $connection = DriverManager::getConnection($connectionParams, $config);

    return new EntityManager($connection, $config);
});


/* interface bindings */
$container->set(EntityManagerInterface::class, fn($c) => $c->get(EntityManager::class));
$container->set(ClockInterface::class, fn() => new SystemClock()); // ⭐ fixed


return $container;
