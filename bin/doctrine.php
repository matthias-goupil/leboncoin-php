<?php
namespace Bin;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Framework\Storage\Doctrine\RepositoryManagerMySQL;
use Config\Environment\Production\ConfigurationProduction;

$e = new RepositoryManagerMySQL(ConfigurationProduction::repositoryManager["dataSourceParameters"]);

$e->bin();