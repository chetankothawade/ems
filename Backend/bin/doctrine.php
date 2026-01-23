#!/usr/bin/env php
<?php
declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;

require __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../config/container.php';

/** @var EntityManager $entityManager */
$entityManager = $container->get(EntityManager::class);

$config = new ConfigurationArray([
    'table_storage' => [
        'table_name' => 'doctrine_migration_versions',
    ],
    'migrations_paths' => [
        'DoctrineMigrations' => __DIR__ . '/../migrations',
    ],
    'all_or_nothing' => true,
    'check_database_platform' => true,
]);

return DependencyFactory::fromEntityManager(
    $config,
    new ExistingEntityManager($entityManager)
);
