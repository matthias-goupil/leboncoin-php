<?php

namespace Config\Environment\Production;

use Config\ConfigurationGlobal;
use Framework\Storage\SQL\RepositoryManagerMySQL;

class ConfigurationProduction extends ConfigurationGlobal
{
    const debug = false;

    const environment = 'production';

    //A configurer
    const repositoryManager = [
        "manager" => RepositoryManagerMySQL::class,
        "dataSourceParameters" => [
            'hostname' => '',
            'database' => '',
            'login' => '',
            'password' => ''
        ]
    ];
}