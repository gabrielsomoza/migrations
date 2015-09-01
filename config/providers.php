<?php

return [
    'providers' => [
        'application' => \Doctrine\DBAL\Migrations\Providers\ApplicationProvider::class,
        'commands' => \Doctrine\DBAL\Migrations\Providers\CommandsProvider::class,
        'doctrine' => \Doctrine\DBAL\Migrations\Providers\DoctrineProvider::class,
        'entity' => \Doctrine\DBAL\Migrations\Providers\EntityProvider::class,
        'helperSet' => \Doctrine\DBAL\Migrations\Providers\HelperSetProvider::class,
        'repository' => \Doctrine\DBAL\Migrations\Providers\RepositoryProvider::class,
        'storage' => \Doctrine\DBAL\Migrations\Providers\StorageProvider::class,
        'timeline' => \Baleen\Cli\Container\ServiceProvider\TimelineProvider::class,
    ],
];
