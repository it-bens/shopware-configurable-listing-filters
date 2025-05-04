<?php

declare(strict_types=1);

use Doctrine\DBAL\Connection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationRepositoryInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\EventFilter;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\EventFilterInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\EventFlattener;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\EventFlattenerInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldInformationCollector;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformation\FilterFieldInformationCollectorInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder\ForNonTranslatedDefinition;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder\ForNonTranslatedDefinitionInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder\ForTranslatedDefinition;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsBuilder\ForTranslatedDefinitionInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollector;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\FilterFieldInformationWithIds\FilterFieldInformationWithIdsCollectorInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\NonStaticEntityDefinitionQueryHelper;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\NonStaticEntityDefinitionQueryHelperInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\QueryExtender;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\QueryExtenderInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\QueryStarter;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidation\QueryStarterInterface;
use ITB\ITBConfigurableListingFilters\Core\Framework\Adapter\Cache\CacheInvalidationSubscriber;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Adapter\Cache\CacheInvalidator;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(EventFlattener::class);
    $services->alias(EventFlattenerInterface::class, EventFlattener::class);
    $services->set(EventFilter::class)->args([service(EventFlattenerInterface::class)]);
    $services->alias(EventFilterInterface::class, EventFilter::class);

    $services->set(NonStaticEntityDefinitionQueryHelper::class);
    $services->alias(NonStaticEntityDefinitionQueryHelperInterface::class, NonStaticEntityDefinitionQueryHelper::class);

    $services->set(FilterFieldInformationCollector::class)->args([
        service(ProductDefinition::class),
        service(NonStaticEntityDefinitionQueryHelperInterface::class),
        service(EntityDefinitionQueryHelper::class),
    ]);
    $services->alias(FilterFieldInformationCollectorInterface::class, FilterFieldInformationCollector::class);

    $services->set(ForNonTranslatedDefinition::class);
    $services->alias(ForNonTranslatedDefinitionInterface::class, ForNonTranslatedDefinition::class);
    $services->set(ForTranslatedDefinition::class);
    $services->alias(ForTranslatedDefinitionInterface::class, ForTranslatedDefinition::class);

    $services->set(FilterFieldInformationWithIdsCollector::class)->args([
        service(ForTranslatedDefinitionInterface::class),
        service(ForNonTranslatedDefinitionInterface::class),
    ]);
    $services->alias(FilterFieldInformationWithIdsCollectorInterface::class, FilterFieldInformationWithIdsCollector::class);

    $services->set(QueryStarter::class)->args([service(Connection::class)]);
    $services->alias(QueryStarterInterface::class, QueryStarter::class);

    $services->set(QueryExtender::class)->args([service(ProductDefinition::class), service(EntityDefinitionQueryHelper::class)]);
    $services->alias(QueryExtenderInterface::class, QueryExtender::class);

    $services->set(CacheInvalidationSubscriber::class)->args([
        service(EventFilterInterface::class),
        service(ListingFilterConfigurationRepositoryInterface::class),
        service(FilterFieldInformationCollectorInterface::class),
        service(FilterFieldInformationWithIdsCollectorInterface::class),
        service(QueryStarterInterface::class),
        service(QueryExtenderInterface::class),
        service(CacheInvalidator::class),
    ])
        ->tag('kernel.event_listener', [
            'event' => EntityWrittenContainerEvent::class,
            'method' => 'invalidate',
        ]);
};
