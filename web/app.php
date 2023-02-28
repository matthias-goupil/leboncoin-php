<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Config\Environment\Production\ConfigurationProduction;
use Symfony\Component\HttpFoundation\Request;
use TheFeed\TheFeed;

$app = new TheFeed();
$container = $app->initializeApplication(ConfigurationProduction::class);
$request = Request::createFromGlobals();

$framework = $container->get('framework');
$response = $framework->handle($request);
$response->send();

// echo "oui";
