<?php

declare(strict_types=1);

use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\FilterBuilder as CheckboxFilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\FilterBuilderInterface as CheckboxFilterBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\RequestValueBuilder as CheckboxRequestValueBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\RequestValueBuilderInterface as CheckboxRequestValueBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront\RenderDataBuilder as CheckboxRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront\RenderDataBuilderInterface as CheckboxRenderDataBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\FilterBuilder as MultiSelectFilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\FilterBuilderInterface as MultiSelectFilterBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\RequestValueBuilder as MultiSelectRequestValueBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\RequestValueBuilderInterface as MultiSelectRequestValueBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\ElementsExtractor as MultiSelectElementsExtractor;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\ElementsExtractorInterface as MultiSelectElementsExtractorInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderDataBuilder as MultiSelectRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderDataBuilderInterface as MultiSelectRenderDataBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelectValueSplitter;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelectValueSplitterInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\FilterBuilder as RangeFilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\FilterBuilderInterface as RangeFilterBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\RequestValueBuilder as RangeRequestValueBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\RequestValueBuilderInterface as RangeRequestValueBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\InputValueExtractor;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\InputValueExtractorInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderDataBuilder as RangeRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderDataBuilderInterface as RangeRenderDataBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\FilterBuilder as RangeIntervalFilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\FilterBuilderForNonCompatibleFields as RangeIntervalFilterBuilderForNonCompatibleFields;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\FilterBuilderInterface as RangeIntervalFilterBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\RequestValueBuilder as RangeIntervalRequestValueBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\RequestValueBuilderInterface as RangeIntervalRequestValueBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\RangeAggregationCompatibilityChecker;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\RangeAggregationCompatibilityCheckerInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\ElementsExtractor as RangeIntervalElementsExtractor;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\ElementsExtractorForNonCompatibleFields as RangeIntervalElementsExtractorForNonCompatibleFields;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\ElementsExtractorInterface as RangeIntervalElementsExtractorInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\RenderDataBuilder as RangeIntervalRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\RenderDataBuilderInterface as RangeIntervalRenderDataBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\ValueFromRequestExtractor;
use ITB\ITBConfigurableListingFilters\ListingFilter\ValueFromRequestExtractorInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use Shopware\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(MultiSelectValueSplitter::class);
    $services->alias(MultiSelectValueSplitterInterface::class, MultiSelectValueSplitter::class);
    $services->set(ValueFromRequestExtractor::class);
    $services->alias(ValueFromRequestExtractorInterface::class, ValueFromRequestExtractor::class);

    $services->set(RangeAggregationCompatibilityChecker::class)->args([
        service(EntityDefinitionQueryHelper::class),
        service(DefinitionInstanceRegistry::class),
    ]);
    $services->alias(RangeAggregationCompatibilityCheckerInterface::class, RangeAggregationCompatibilityChecker::class);

    // DAL listing filter services
    $services->set(CheckboxFilterBuilder::class);
    $services->alias(CheckboxFilterBuilderInterface::class, CheckboxFilterBuilder::class);
    $services->set(CheckboxRequestValueBuilder::class);
    $services->alias(CheckboxRequestValueBuilderInterface::class, CheckboxRequestValueBuilder::class);

    $services->set(MultiSelectFilterBuilder::class);
    $services->alias(MultiSelectFilterBuilderInterface::class, MultiSelectFilterBuilder::class);
    $services->set(MultiSelectRequestValueBuilder::class)->args([
        service(ValueFromRequestExtractorInterface::class),
        service(MultiSelectValueSplitterInterface::class),
    ]);
    $services->alias(MultiSelectRequestValueBuilderInterface::class, MultiSelectRequestValueBuilder::class);

    $services->set(RangeFilterBuilder::class);
    $services->alias(RangeFilterBuilderInterface::class, RangeFilterBuilder::class);
    $services->set(RangeRequestValueBuilder::class);
    $services->alias(RangeRequestValueBuilderInterface::class, RangeRequestValueBuilder::class);

    $services->set(RangeIntervalFilterBuilderForNonCompatibleFields::class);
    $services->set(RangeIntervalFilterBuilder::class)->args([
        service(RangeAggregationCompatibilityCheckerInterface::class),
        service(RangeIntervalFilterBuilderForNonCompatibleFields::class),
    ]);
    $services->alias(RangeIntervalFilterBuilderInterface::class, RangeIntervalFilterBuilder::class);
    $services->set(RangeIntervalRequestValueBuilder::class)->args([
        service(ValueFromRequestExtractorInterface::class),
        service(MultiSelectValueSplitterInterface::class),
    ]);
    $services->alias(RangeIntervalRequestValueBuilderInterface::class, RangeIntervalRequestValueBuilder::class);

    // Storefront listing filter services
    $services->set(CheckboxRenderDataBuilder::class)->args([service('translator')]);
    $services->alias(CheckboxRenderDataBuilderInterface::class, CheckboxRenderDataBuilder::class);

    $services->set(MultiSelectElementsExtractor::class);
    $services->alias(MultiSelectElementsExtractorInterface::class, MultiSelectElementsExtractor::class);
    $services->set(MultiSelectRenderDataBuilder::class)->args(
        [service(MultiSelectElementsExtractorInterface::class), service('translator')]
    );
    $services->alias(MultiSelectRenderDataBuilderInterface::class, MultiSelectRenderDataBuilder::class);

    $services->set(InputValueExtractor::class);
    $services->alias(InputValueExtractorInterface::class, InputValueExtractor::class);
    $services->set(RangeRenderDataBuilder::class)->args([service(InputValueExtractorInterface::class), service('translator')]);
    $services->alias(RangeRenderDataBuilderInterface::class, RangeRenderDataBuilder::class);

    $services->set(RangeIntervalElementsExtractorForNonCompatibleFields::class);
    $services->set(RangeIntervalElementsExtractor::class)->args([
        service(RangeAggregationCompatibilityCheckerInterface::class),
        service(RangeIntervalElementsExtractorForNonCompatibleFields::class),
    ]);
    $services->alias(RangeIntervalElementsExtractorInterface::class, RangeIntervalElementsExtractor::class);
    $services->set(RangeIntervalRenderDataBuilder::class)->args(
        [service(RangeIntervalElementsExtractorInterface::class), service('translator')]
    );
    $services->alias(RangeIntervalRenderDataBuilderInterface::class, RangeIntervalRenderDataBuilder::class);
};
