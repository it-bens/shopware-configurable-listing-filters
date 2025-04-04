<?php

declare(strict_types=1);

use ITB\ITBConfigurableListingFilters\Twig\MultiSelectFilterElementExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    // Twig extensions
    $services->set(MultiSelectFilterElementExtension::class)->tag('twig.extension');
};
