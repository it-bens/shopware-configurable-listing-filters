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
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\ValueFromRequestExtractor;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\ValueFromRequestExtractorInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\ElementsExtractor;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\ElementsExtractorInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderDataBuilder as MultiSelectRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderDataBuilderInterface as MultiSelectRenderDataBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\FilterBuilder as RangeFilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\FilterBuilderInterface as RangeFilterBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\RequestValueBuilder as RangeRequestValueBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\RequestValueBuilderInterface as RangeRequestValueBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\InputValueExtractor;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\InputValueExtractorInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderDataBuilder as RangeRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderDataBuilderInterface as RangeRenderDataBuilderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    // DAL listing filter services
    $services->set(CheckboxFilterBuilder::class);
    $services->alias(CheckboxFilterBuilderInterface::class, CheckboxFilterBuilder::class);
    $services->set(CheckboxRequestValueBuilder::class);
    $services->alias(CheckboxRequestValueBuilderInterface::class, CheckboxRequestValueBuilder::class);

    $services->set(ValueFromRequestExtractor::class);
    $services->alias(ValueFromRequestExtractorInterface::class, ValueFromRequestExtractor::class);
    $services->set(MultiSelectFilterBuilder::class);
    $services->alias(MultiSelectFilterBuilderInterface::class, MultiSelectFilterBuilder::class);
    $services->set(MultiSelectRequestValueBuilder::class)->args([service(ValueFromRequestExtractorInterface::class)]);
    $services->alias(MultiSelectRequestValueBuilderInterface::class, MultiSelectRequestValueBuilder::class);

    $services->set(RangeFilterBuilder::class);
    $services->alias(RangeFilterBuilderInterface::class, RangeFilterBuilder::class);
    $services->set(RangeRequestValueBuilder::class);
    $services->alias(RangeRequestValueBuilderInterface::class, RangeRequestValueBuilder::class);

    // Storefront listing filter services
    $services->set(CheckboxRenderDataBuilder::class)->args([service('translator')]);
    $services->alias(CheckboxRenderDataBuilderInterface::class, CheckboxRenderDataBuilder::class);

    $services->set(ElementsExtractor::class);
    $services->alias(ElementsExtractorInterface::class, ElementsExtractor::class);
    $services->set(MultiSelectRenderDataBuilder::class)->args([service(ElementsExtractorInterface::class), service('translator')]);
    $services->alias(MultiSelectRenderDataBuilderInterface::class, MultiSelectRenderDataBuilder::class);

    $services->set(InputValueExtractor::class);
    $services->alias(InputValueExtractorInterface::class, InputValueExtractor::class);
    $services->set(RangeRenderDataBuilder::class)->args([service(InputValueExtractorInterface::class), service('translator')]);
    $services->alias(RangeRenderDataBuilderInterface::class, RangeRenderDataBuilder::class);
};
