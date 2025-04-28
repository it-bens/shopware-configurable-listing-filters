<?php

declare(strict_types=1);

use ITB\ITBConfigurableListingFilters\Core\Content\Cms\ProductListingPageSubscriber;
use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\ProductListingAggregationsExtractor;
use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\ProductListingAggregationsExtractorInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\RenderDataCollectionBuilder;
use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\RenderDataCollectionBuilderInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\SidebarFilterCmsSlotsExtractor;
use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\SidebarFilterCmsSlotsExtractorInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate\CheckboxListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationRepository;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationRepositoryInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\Aggregate\MultiSelectListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\Aggregate\RangeListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationTranslationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationDefinition;
use ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\ProductListingSubscriber;
use ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\Service\FilterCollectionEnricher;
use ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\Service\FilterCollectionEnricherInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\FilterBuilderInterface as CheckboxFilterBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\RequestValueBuilderInterface as CheckboxRequestValueBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront\RenderDataBuilderInterface as CheckboxRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\FilterBuilderInterface as MultiSelectFilterBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\RequestValueBuilderInterface as MultiSelectRequestValueBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderDataBuilderInterface as MultiSelectRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\FilterBuilderInterface as RangeFilterBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\RequestValueBuilderInterface as RangeRequestValueBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderDataBuilderInterface as RangeRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\FilterBuilderInterface as RangeIntervalFilterBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\RequestValueBuilderInterface as RangeIntervalRequestValueBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\RenderDataBuilderInterface as RangeIntervalRenderDataBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    // Entity definitions
    $services->set(CheckboxListingFilterConfigurationDefinition::class)->tag('shopware.entity.definition', [
        'entity' => CheckboxListingFilterConfigurationDefinition::ENTITY_NAME,
    ]);
    $services->set(CheckboxListingFilterConfigurationTranslationDefinition::class)->tag('shopware.entity.definition', [
        'entity' => CheckboxListingFilterConfigurationTranslationDefinition::ENTITY_NAME,
    ]);
    $services->set(MultiSelectListingFilterConfigurationDefinition::class)->tag('shopware.entity.definition', [
        'entity' => MultiSelectListingFilterConfigurationDefinition::ENTITY_NAME,
    ]);
    $services->set(MultiSelectListingFilterConfigurationTranslationDefinition::class)->tag('shopware.entity.definition', [
        'entity' => MultiSelectListingFilterConfigurationTranslationDefinition::ENTITY_NAME,
    ]);
    $services->set(RangeListingFilterConfigurationDefinition::class)->tag('shopware.entity.definition', [
        'entity' => RangeListingFilterConfigurationDefinition::ENTITY_NAME,
    ]);
    $services->set(RangeListingFilterConfigurationTranslationDefinition::class)->tag('shopware.entity.definition', [
        'entity' => RangeListingFilterConfigurationTranslationDefinition::ENTITY_NAME,
    ]);
    $services->set(RangeIntervalListingFilterConfigurationDefinition::class)->tag('shopware.entity.definition', [
        'entity' => RangeIntervalListingFilterConfigurationDefinition::ENTITY_NAME,
    ]);
    $services->set(RangeIntervalListingFilterConfigurationTranslationDefinition::class)->tag('shopware.entity.definition', [
        'entity' => RangeIntervalListingFilterConfigurationTranslationDefinition::ENTITY_NAME,
    ]);
    $services->set(RangeIntervalListingFilterConfigurationIntervalDefinition::class)->tag('shopware.entity.definition', [
        'entity' => RangeIntervalListingFilterConfigurationIntervalDefinition::ENTITY_NAME,
    ]);

    // Entity repositories
    $services->set(ListingFilterConfigurationRepository::class)
        ->args([
            service(CheckboxListingFilterConfigurationDefinition::ENTITY_NAME . '.repository'),
            service(MultiSelectListingFilterConfigurationDefinition::ENTITY_NAME . '.repository'),
            service(RangeListingFilterConfigurationDefinition::ENTITY_NAME . '.repository'),
            service(RangeIntervalListingFilterConfigurationDefinition::ENTITY_NAME . '.repository'),
        ])
        ->alias(ListingFilterConfigurationRepositoryInterface::class, ListingFilterConfigurationRepository::class);

    // Listing subscriber and helper services
    $services->set(FilterCollectionEnricher::class)
        ->args([
            service(CheckboxRequestValueBuilderInterface::class),
            service(CheckboxFilterBuilderInterface::class),
            service(MultiSelectRequestValueBuilderInterface::class),
            service(MultiSelectFilterBuilderInterface::class),
            service(RangeRequestValueBuilderInterface::class),
            service(RangeFilterBuilderInterface::class),
            service(RangeIntervalRequestValueBuilderInterface::class),
            service(RangeIntervalFilterBuilderInterface::class),
        ]);
    $services->alias(FilterCollectionEnricherInterface::class, FilterCollectionEnricher::class);
    $services->set(ProductListingSubscriber::class)
        ->args([service(ListingFilterConfigurationRepositoryInterface::class), service(FilterCollectionEnricherInterface::class)])
        ->tag('kernel.event_subscriber');

    // Listing page subscriber and helper services
    $services->set(ProductListingPageSubscriber::class)
        ->args([
            service(SidebarFilterCmsSlotsExtractorInterface::class),
            service(ProductListingAggregationsExtractorInterface::class),
            service(ListingFilterConfigurationRepositoryInterface::class),
            service(RenderDataCollectionBuilderInterface::class),
        ])
        ->tag('kernel.event_subscriber');
    $services->set(ProductListingAggregationsExtractor::class);
    $services->alias(ProductListingAggregationsExtractorInterface::class, ProductListingAggregationsExtractor::class);
    $services->set(RenderDataCollectionBuilder::class)
        ->args([
            service(CheckboxRenderDataBuilder::class),
            service(MultiSelectRenderDataBuilder::class),
            service(RangeRenderDataBuilder::class),
            service(RangeIntervalRenderDataBuilder::class),
        ]);
    $services->alias(RenderDataCollectionBuilderInterface::class, RenderDataCollectionBuilder::class);
    $services->set(SidebarFilterCmsSlotsExtractor::class);
    $services->alias(SidebarFilterCmsSlotsExtractorInterface::class, SidebarFilterCmsSlotsExtractor::class);
};
