<?php

return [
    'providers' => [
        'application' => \Doctrine\DBAL\Migrations\Provider\ApplicationProvider::class,
        'commands' => \Doctrine\DBAL\Migrations\Provider\CommandsProvider::class,
        'doctrine' => \Doctrine\DBAL\Migrations\Provider\DoctrineProvider::class,
        'entity' => \Doctrine\DBAL\Migrations\Provider\EntityProvider::class,
        'helperSet' => \Doctrine\DBAL\Migrations\Provider\HelperSetProvider::class,
        'repository' => \Doctrine\DBAL\Migrations\Provider\RepositoryProvider::class,
        'storage' => \Doctrine\DBAL\Migrations\Provider\StorageProvider::class,
        'timeline' => \Baleen\Cli\Container\ServiceProvider\TimelineProvider::class,
    ],
];
