<?php

return [
    'providers' => [
        'application' => \Doctrine\DBAL\Migrations\Providers\ApplicationProvider::class,
        'storage' => \Doctrine\DBAL\Migrations\Providers\StorageProvider::class,
        'repository' => \Baleen\Cli\Container\ServiceProvider\RepositoryProvider::class,
        'timeline' => \Baleen\Cli\Container\ServiceProvider\TimelineProvider::class,
        'helperSet' => \Baleen\Cli\Container\ServiceProvider\HelperSetProvider::class,
        'commands' => \Baleen\Cli\Container\ServiceProvider\CommandsProvider::class,
        'entity' => \Doctrine\DBAL\Migrations\Providers\EntityProvider::class,
    ],
];
