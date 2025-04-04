<?php

declare(strict_types=1);

use ITB\ITBConfigurableListingFilters\Console\CreateCheckboxListingFilterConfigurationCommand;
use ITB\ITBConfigurableListingFilters\Console\CreateMultiSelectListingFilterConfigurationCommand;
use ITB\ITBConfigurableListingFilters\Console\CreateRangeListingFilterConfigurationCommand;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    // Console commands
    $services->set(CreateCheckboxListingFilterConfigurationCommand::class)
        ->args([service('itb_listing_filter_configuration_checkbox.repository')])
        ->tag('console.command');
    $services->set(CreateMultiSelectListingFilterConfigurationCommand::class)
        ->args([service('itb_listing_filter_configuration_multi_select.repository')])
        ->tag('console.command');
    $services->set(CreateRangeListingFilterConfigurationCommand::class)
        ->args([service('itb_listing_filter_configuration_range.repository')])
        ->tag('console.command');
};
