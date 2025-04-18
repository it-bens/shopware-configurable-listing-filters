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
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront\RenderData as CheckboxRenderData;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront\RenderDataBuilderInterface as CheckboxRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\ElementCollection;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderData as MultiSelectRenderData;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderDataBuilderInterface as MultiSelectRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderData as RangeRenderData;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderDataBuilderInterface as RangeRenderDataBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\RenderDataBuilderInterface as RangeIntervalRenderDataBuilder;
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
        $multiSelectRenderData = new MultiSelectRenderData(
            'template',
            'multi-select',
            'label',
            'pluginSelector',
            new ElementCollection([], []),
            'tooltip'
        );

        $rangeListingFilterConfiguration = new RangeListingFilterConfigurationEntity();
        $rangeListingFilterConfiguration->setUniqueIdentifier('range');
        $rangeListingFilterConfiguration->setDalField('range');
        $rangeListingFilterConfiguration->setEnabled(true);

        $rangeListingFilterConfigurationCollection = new RangeListingFilterConfigurationCollection([$rangeListingFilterConfiguration]);
        $rangeRenderData = new RangeRenderData('template', 'range', 'label', 'paramenter', 'paramenter', null, null, null, 'tooltip');

        $rangeIntervalListingFilterConfiguration = new RangeIntervalListingFilterConfigurationEntity();
        $rangeIntervalListingFilterConfiguration->setUniqueIdentifier('range-interval');
        $rangeIntervalListingFilterConfiguration->setDalField('range-interval');
        $rangeIntervalListingFilterConfiguration->setEnabled(true);

        $rangeIntervalListingFilterConfigurationCollection = new RangeIntervalListingFilterConfigurationCollection([
            $rangeIntervalListingFilterConfiguration,
        ]);
        $rangeIntervalRenderData = new MultiSelectRenderData('template', 'range-interval', 'label', 'paramenter', [], 'tooltip');

        $listingFilterConfigurationCollection = new ListingFilterConfigurationCollection(
            $checkboxListingFilterConfigurationCollection,
            $multiSelectListingFilterConfigurationCollection,
            $rangeListingFilterConfigurationCollection,
            $rangeIntervalListingFilterConfigurationCollection,
        );

        yield 'with enabled configurations' => [
            $listingFilterConfigurationCollection,
            new AggregationResultCollection(),
            $checkboxRenderData,
            $multiSelectRenderData,
            $rangeRenderData,
            $rangeIntervalRenderData,
            [$checkboxRenderData, $multiSelectRenderData, $rangeRenderData, $rangeIntervalRenderData],
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

        $rangeIntervalListingFilterConfiguration = new RangeIntervalListingFilterConfigurationEntity();
        $rangeIntervalListingFilterConfiguration->setUniqueIdentifier('range-interval');
        $rangeIntervalListingFilterConfiguration->setDalField('range-interval');
        $rangeIntervalListingFilterConfiguration->setEnabled(false);

        $rangeIntervalListingFilterConfigurationCollection = new RangeIntervalListingFilterConfigurationCollection([
            $rangeIntervalListingFilterConfiguration,
        ]);

        $listingFilterConfigurationCollection = new ListingFilterConfigurationCollection(
            $checkboxListingFilterConfigurationCollection,
            $multiSelectListingFilterConfigurationCollection,
            $rangeListingFilterConfigurationCollection,
            $rangeIntervalListingFilterConfigurationCollection,
        );

        yield 'with disabled configurations' => [
            $listingFilterConfigurationCollection,
            new AggregationResultCollection(),
            $checkboxRenderData,
            $multiSelectRenderData,
            $rangeRenderData,
            $rangeIntervalRenderData,
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
        MultiSelectRenderData $rangeIntervalSelectRenderData,
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
        $rangeIntervalRenderDataBuilder = $this->createMock(RangeIntervalRenderDataBuilder::class);
        $rangeIntervalRenderDataBuilder->method('buildRenderData')
            ->willReturn($rangeIntervalSelectRenderData);

        $renderDataCollectionBuilder = new RenderDataCollectionBuilder(
            $checkboxRenderDataBuilder,
            $multiSelectRenderDataBuilder,
            $rangeRenderDataBuilder,
            $rangeIntervalRenderDataBuilder
        );

        $renderDataCollection = $renderDataCollectionBuilder->buildRenderDataCollection(
            $listingFilterConfigurationCollection,
            $aggregationResult
        );
        $this->assertEquals($expectedRenderDatasets, $renderDataCollection->getRenderDatasets());
    }
}
