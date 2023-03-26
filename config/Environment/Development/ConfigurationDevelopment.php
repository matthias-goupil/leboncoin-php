<?php

namespace Config\Environment\Development;

use Config\ConfigurationGlobal;
use Framework\Storage\Doctrine\RepositoryManagerMySQL;
use Framework\Storage\SQL\RepositoryManagerSQLite;

class ConfigurationDevelopment extends ConfigurationGlobal
{
    const debug = true;

    const environment = 'development';

    const repositoryManager = [
//        "manager" => RepositoryManagerSQLite::class,
//        "doctrine-manager" => RepositoryManagerMySQL::class,
        "manager" => \Framework\Storage\Doctrine\RepositoryManagerMySQL::class,
        "dataSourceParameters" => [
            'file' => __DIR__.'/database_development',
        ]
    ];
}