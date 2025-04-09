<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import('services/core.php');
    $containerConfigurator->import('services/listing_filter.php');
    $containerConfigurator->import('services/twig.php');
};
