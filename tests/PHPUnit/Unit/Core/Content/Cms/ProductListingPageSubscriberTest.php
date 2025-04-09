<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\Cms;

use ITB\ITBConfigurableListingFilters\Core\Content\Cms\ProductListingPageSubscriber;
use ITB\ITBConfigurableListingFilters\Core\Content\Cms\RenderDataCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\ProductListingAggregationsExtractorInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\RenderDataCollectionBuilderInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\SidebarFilterCmsSlotsExtractorInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationRepositoryInterface;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront\RenderData as CheckboxRenderData;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderData as MultiSelectRenderData;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderData as RangeRenderData;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\CmsPageCollection;
use Shopware\Core\Content\Cms\Events\CmsPageLoadedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(ProductListingPageSubscriber::class)]
final class ProductListingPageSubscriberTest extends TestCase
{
    public static function addStorefrontFiltersProvider(): \Generator
    {
        $checkboxRenderData = new CheckboxRenderData('template', 'checkbox', 'label', 'tooltip');
        $multiSelectRenderData = new MultiSelectRenderData('template', 'multi-select', 'label', 'pluginSelector', [], 'tooltip');
        $rangeRenderData = new RangeRenderData('template', 'range', 'label', 'parameter', 'parameter', null, null, null, 'tooltip');

        yield [$checkboxRenderData, $multiSelectRenderData, $rangeRenderData];
    }

    #[DataProvider('addStorefrontFiltersProvider')]
    public function testAddStorefrontFilters(
        CheckboxRenderData $checkboxRenderData,
        MultiSelectRenderData $multiSelectRenderData,
        RangeRenderData $rangeRenderData
    ): void {
        $cmsPageCollection = self::createStub(CmsPageCollection::class);

        $sidebarFilterCmsSlot = $this->createMock(CmsSlotEntity::class);
        $sidebarFilterCmsSlot->method('addExtension')
            ->willReturnCallback(function ($nameArgument, $extensionArgument): void {
                $this->assertSame(RenderDataCollection::NAME, $nameArgument);
                $this->assertInstanceOf(RenderDataCollection::class, $extensionArgument);
                $this->assertCount(3, $extensionArgument->getRenderDatasets());
            });

        $sidebarFilterCmsSlotsExtractor = $this->createMock(SidebarFilterCmsSlotsExtractorInterface::class);
        $sidebarFilterCmsSlotsExtractor->method('extractSidebarFilterCmsSlots')
            ->willReturnCallback(function ($cmsPageCollectionArgument) use ($cmsPageCollection, $sidebarFilterCmsSlot): array {
                $this->assertEquals($cmsPageCollectionArgument, $cmsPageCollection);

                return [$sidebarFilterCmsSlot];
            });

        $aggregationResults = new AggregationResultCollection();
        $productListingAggregationsExtractor = $this->createMock(ProductListingAggregationsExtractorInterface::class);
        $productListingAggregationsExtractor->method('extractProductListingAggregations')
            ->willReturnCallback(function ($cmsPageCollectionArgument) use (
                $cmsPageCollection,
                $aggregationResults
            ): AggregationResultCollection {
                $this->assertEquals($cmsPageCollectionArgument, $cmsPageCollection);

                return $aggregationResults;
            });

        $listingFilterConfigurationRepository = $this->createMock(ListingFilterConfigurationRepositoryInterface::class);
        $checkboxListingFilterConfigurationCollection = new CheckboxListingFilterConfigurationCollection();
        $listingFilterConfigurationRepository->method('getCheckboxListingFilterConfigurations')
            ->willReturn($checkboxListingFilterConfigurationCollection);
        $multiSelectListingFilterConfigurationCollection = new MultiSelectListingFilterConfigurationCollection();
        $listingFilterConfigurationRepository->method('getMultiSelectListingFilterConfigurations')
            ->willReturn($multiSelectListingFilterConfigurationCollection);
        $rangeListingFilterConfigurationCollection = new RangeListingFilterConfigurationCollection();
        $listingFilterConfigurationRepository->method('getRangeListingFilterConfigurations')
            ->willReturn($rangeListingFilterConfigurationCollection);

        $renderDataCollectionBuilder = $this->createMock(RenderDataCollectionBuilderInterface::class);
        $renderDataCollectionBuilder->method('buildRenderDataCollection')
            ->willReturnCallback(function ($listingFilterConfigurationCollectionArgument, $aggregationResultsArgument) use (
                $aggregationResults,
                $checkboxRenderData,
                $multiSelectRenderData,
                $rangeRenderData
            ): RenderDataCollection {
                $this->assertEquals($aggregationResultsArgument, $aggregationResults);

                $renderDataCollection = new RenderDataCollection();
                $renderDataCollection->add($checkboxRenderData);
                $renderDataCollection->add($multiSelectRenderData);
                $renderDataCollection->add($rangeRenderData);

                return $renderDataCollection;
            });

        $productListingPageSubscriber = new ProductListingPageSubscriber(
            $sidebarFilterCmsSlotsExtractor,
            $productListingAggregationsExtractor,
            $listingFilterConfigurationRepository,
            $renderDataCollectionBuilder
        );

        $event = new CmsPageLoadedEvent(
            self::createStub(Request::class),
            $cmsPageCollection,
            self::createStub(SalesChannelContext::class),
        );

        $productListingPageSubscriber->addStorefrontFilters($event);
    }

    public function testAddStorefrontFiltersWithSidebarFilterCmsSlots(): void
    {
        $sidebarFilterCmsSlotsExtractor = self::createStub(SidebarFilterCmsSlotsExtractorInterface::class);
        $sidebarFilterCmsSlotsExtractor->method('extractSidebarFilterCmsSlots')
            ->willReturn([]);
        $productListingAggregationsExtractor = $this->createMock(ProductListingAggregationsExtractorInterface::class);
        $productListingAggregationsExtractor->expects($this->never())
            ->method('extractProductListingAggregations');
        $listingFilterConfigurationRepository = $this->createMock(ListingFilterConfigurationRepositoryInterface::class);
        $listingFilterConfigurationRepository->expects($this->never())
            ->method('getCheckboxListingFilterConfigurations');
        $renderDataCollectionBuilder = $this->createMock(RenderDataCollectionBuilderInterface::class);
        $renderDataCollectionBuilder->expects($this->never())
            ->method('buildRenderDataCollection');

        $productListingPageSubscriber = new ProductListingPageSubscriber(
            $sidebarFilterCmsSlotsExtractor,
            $productListingAggregationsExtractor,
            $listingFilterConfigurationRepository,
            $renderDataCollectionBuilder
        );

        $event = new CmsPageLoadedEvent(
            self::createStub(Request::class),
            self::createStub(CmsPageCollection::class),
            self::createStub(SalesChannelContext::class),
        );

        $productListingPageSubscriber->addStorefrontFilters($event);
    }
}
