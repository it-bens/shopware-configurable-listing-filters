<?php declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

$pluginDirectory = dirname(__DIR__, 2);
$pluginDirs = \scandir($pluginDirectory);

if (!\is_array($pluginDirs)) {
    echo 'Could not check plugin directory';
    $pluginDirs = [];
}

$projectRoot = dirname(__DIR__, 4);
$classLoader = require $projectRoot . '/vendor/autoload.php';
if (file_exists($projectRoot . '/.env')) {
    (new Dotenv())->usePutEnv()->load($projectRoot . '/.env');
}