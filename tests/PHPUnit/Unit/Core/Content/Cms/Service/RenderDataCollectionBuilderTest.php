<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\Cms\Service;

use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\RenderDataCollectionBuilder;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront\RenderData as CheckboxRenderData;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront\RenderDataBuilderInterface as CheckboxRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderData as MultiSelectRenderData;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderDataBuilderInterface as MultiSelectRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderData as RangeRenderData;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderDataBuilderInterface as RangeRenderDataBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;

#[CoversClass(RenderDataCollectionBuilder::class)]
final class RenderDataCollectionBuilderTest extends TestCase
{
    public static function buildRenderDataCollectionProvider(): \Generator
    {
        $checkboxListingFilterConfiguration = new CheckboxListingFilterConfigurationEntity();
        $checkboxListingFilterConfiguration->setUniqueIdentifier('checkbox');
        $checkboxListingFilterConfiguration->setDalField('checkbox');
        $checkboxListingFilterConfiguration->setEnabled(true);

        $checkboxListingFilterConfigurationCollection = new CheckboxListingFilterConfigurationCollection([
            $checkboxListingFilterConfiguration,
        ]);
        $checkboxRenderData = new CheckboxRenderData('template', 'checkbox', 'label', 'tooltip');

        $multiSelectListingFilterConfiguration = new MultiSelectListingFilterConfigurationEntity();
        $multiSelectListingFilterConfiguration->setUniqueIdentifier('multi-select');
        $multiSelectListingFilterConfiguration->setDalField('multi-select');
        $multiSelectListingFilterConfiguration->setEnabled(true);

        $multiSelectListingFilterConfigurationCollection = new MultiSelectListingFilterConfigurationCollection([
            $multiSelectListingFilterConfiguration,
        ]);
        $multiSelectRenderData = new MultiSelectRenderData('template', 'multi-select', 'label', 'pluginSelector', [], 'tooltip');

        $rangeListingFilterConfiguration = new RangeListingFilterConfigurationEntity();
        $rangeListingFilterConfiguration->setUniqueIdentifier('range');
        $rangeListingFilterConfiguration->setDalField('range');
        $rangeListingFilterConfiguration->setEnabled(true);

        $rangeListingFilterConfigurationCollection = new RangeListingFilterConfigurationCollection([$rangeListingFilterConfiguration]);
        $rangeRenderData = new RangeRenderData('template', 'range', 'label', 'paramenter', 'paramenter', null, null, null, 'tooltip');

        $listingFilterConfigurationCollection = new ListingFilterConfigurationCollection(
            $checkboxListingFilterConfigurationCollection,
            $multiSelectListingFilterConfigurationCollection,
            $rangeListingFilterConfigurationCollection,
        );

        yield 'with enabled configurations' => [
            $listingFilterConfigurationCollection,
            new AggregationResultCollection(),
            $checkboxRenderData,
            $multiSelectRenderData,
            $rangeRenderData,
            [$checkboxRenderData, $multiSelectRenderData, $rangeRenderData],
        ];

        $checkboxListingFilterConfiguration = new CheckboxListingFilterConfigurationEntity();
        $checkboxListingFilterConfiguration->setUniqueIdentifier('checkbox');
        $checkboxListingFilterConfiguration->setDalField('checkbox');
        $checkboxListingFilterConfiguration->setEnabled(false);

        $checkboxListingFilterConfigurationCollection = new CheckboxListingFilterConfigurationCollection([
            $checkboxListingFilterConfiguration,
        ]);

        $multiSelectListingFilterConfiguration = new MultiSelectListingFilterConfigurationEntity();
        $multiSelectListingFilterConfiguration->setUniqueIdentifier('multi-select');
        $multiSelectListingFilterConfiguration->setDalField('multi-select');
        $multiSelectListingFilterConfiguration->setEnabled(false);

        $multiSelectListingFilterConfigurationCollection = new MultiSelectListingFilterConfigurationCollection([
            $multiSelectListingFilterConfiguration,
        ]);

        $rangeListingFilterConfiguration = new RangeListingFilterConfigurationEntity();
        $rangeListingFilterConfiguration->setUniqueIdentifier('range');
        $rangeListingFilterConfiguration->setDalField('range');
        $rangeListingFilterConfiguration->setEnabled(false);

        $rangeListingFilterConfigurationCollection = new RangeListingFilterConfigurationCollection([$rangeListingFilterConfiguration]);

        $listingFilterConfigurationCollection = new ListingFilterConfigurationCollection(
            $checkboxListingFilterConfigurationCollection,
            $multiSelectListingFilterConfigurationCollection,
            $rangeListingFilterConfigurationCollection,
        );

        yield 'with disabled configurations' => [
            $listingFilterConfigurationCollection,
            new AggregationResultCollection(),
            $checkboxRenderData,
            $multiSelectRenderData,
            $rangeRenderData,
            [],
        ];
    }

    /**
     * @param array<CheckboxRenderData|MultiSelectRenderData|RangeRenderData> $expectedRenderDatasets
     */
    #[DataProvider('buildRenderDataCollectionProvider')]
    public function testBuildRenderDataCollection(
        ListingFilterConfigurationCollection $listingFilterConfigurationCollection,
        AggregationResultCollection $aggregationResult,
        CheckboxRenderData $checkboxRenderData,
        MultiSelectRenderData $multiSelectRenderData,
        RangeRenderData $rangeRenderData,
        array $expectedRenderDatasets,
    ): void {
        $checkboxRenderDataBuilder = $this->createMock(CheckboxRenderDataBuilder::class);
        $checkboxRenderDataBuilder->method('buildRenderData')
            ->willReturn($checkboxRenderData);
        $multiSelectRenderDataBuilder = $this->createMock(MultiSelectRenderDataBuilder::class);
        $multiSelectRenderDataBuilder->method('buildRenderData')
            ->willReturn($multiSelectRenderData);
        $rangeRenderDataBuilder = $this->createMock(RangeRenderDataBuilder::class);
        $rangeRenderDataBuilder->method('buildRenderData')
            ->willReturn($rangeRenderData);

        $renderDataCollectionBuilder = new RenderDataCollectionBuilder(
            $checkboxRenderDataBuilder,
            $multiSelectRenderDataBuilder,
            $rangeRenderDataBuilder
        );

        $renderDataCollection = $renderDataCollectionBuilder->buildRenderDataCollection(
            $listingFilterConfigurationCollection,
            $aggregationResult
        );
        $this->assertEquals($expectedRenderDatasets, $renderDataCollection->getRenderDatasets());
    }
}
