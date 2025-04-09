<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\Cms;

use ITB\ITBConfigurableListingFilters\Core\Content\Cms\RenderDataCollection;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront\RenderData as CheckboxRenderData;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderData as MultiSelectRenderData;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderData as RangeRenderData;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(RenderDataCollection::class)]
final class RenderDataCollectionTest extends TestCase
{
    public static function addProvider(): \Generator
    {
        $renderData = new CheckboxRenderData('template', 'name', 'displayName', 'disabledFilterTooltip');

        yield [new RenderDataCollection(), $renderData, [$renderData]];
    }

    public static function getRenderDatasetsProvider(): \Generator
    {
        $renderData = new CheckboxRenderData('template', 'name', 'displayName', 'disabledFilterTooltip');
        $renderDataCollection = new RenderDataCollection();
        $renderDataCollection->add($renderData);

        yield [$renderDataCollection, [$renderData]];
    }

    /**
     * @param array<CheckboxRenderData|MultiSelectRenderData|RangeRenderData> $expectedGetRenderDatasets
     */
    #[DataProvider('addProvider')]
    public function testAdd(
        RenderDataCollection $collection,
        CheckboxRenderData|MultiSelectRenderData|RangeRenderData $renderData,
        array $expectedGetRenderDatasets
    ): void {
        $collection->add($renderData);
        $this->assertEquals($expectedGetRenderDatasets, $collection->getRenderDatasets());
    }

    /**
     * @param array<CheckboxRenderData|MultiSelectRenderData|RangeRenderData> $expectedGetRenderDatasets
     */
    #[DataProvider('getRenderDatasetsProvider')]
    public function testGetRenderDatasets(RenderDataCollection $collection, array $expectedGetRenderDatasets): void
    {
        $this->assertEquals($expectedGetRenderDatasets, $collection->getRenderDatasets());
    }
}
