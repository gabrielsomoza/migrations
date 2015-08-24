<?php

return [
    'providers' => [
        'application' => \Doctrine\DBAL\Migrations\Providers\ApplicationProvider::class,
        'commands' => \Baleen\Cli\Container\ServiceProvider\CommandsProvider::class,
        'doctrine' => \Doctrine\DBAL\Migrations\Providers\DoctrineProvider::class,
        'entity' => \Doctrine\DBAL\Migrations\Providers\EntityProvider::class,
        'helperSet' => \Doctrine\DBAL\Migrations\Providers\HelperSetProvider::class,
        'repository' => \Baleen\Cli\Container\ServiceProvider\RepositoryProvider::class,
        'storage' => \Doctrine\DBAL\Migrations\Providers\StorageProvider::class,
        'timeline' => \Baleen\Cli\Container\ServiceProvider\TimelineProvider::class,
    ],
];
