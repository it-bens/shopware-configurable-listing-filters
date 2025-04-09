<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\Cms\Service;

use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\ProductListingAggregationsExtractor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Shopware\Core\Content\Cms\Aggregate\CmsBlock\CmsBlockCollection;
use Shopware\Core\Content\Cms\Aggregate\CmsBlock\CmsBlockEntity;
use Shopware\Core\Content\Cms\Aggregate\CmsSection\CmsSectionCollection;
use Shopware\Core\Content\Cms\Aggregate\CmsSection\CmsSectionEntity;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotCollection;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\CmsPageCollection;
use Shopware\Core\Content\Cms\CmsPageEntity;
use Shopware\Core\Content\Cms\SalesChannel\Struct\ProductListingStruct;
use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;

#[CoversClass(ProductListingAggregationsExtractor::class)]
final class ProductListingAggregationsExtractorTest extends TestCase
{
    public static function extractProductListingAggregationsProvider(): \Generator
    {
        $productListingAggregationsExtractor = new ProductListingAggregationsExtractor();

        $aggregationResultCollection = new AggregationResultCollection();

        $productListingResult = self::createStub(ProductListingResult::class);
        $productListingResult->method('getAggregations')
            ->willReturn($aggregationResultCollection);

        $productListingStruct = new ProductListingStruct();
        $productListingStruct->setListing($productListingResult);

        $productListingCmsSlot = new CmsSlotEntity();
        $productListingCmsSlot->setUniqueIdentifier('1');
        $productListingCmsSlot->setSlot('dont-care');
        $productListingCmsSlot->setType('product-listing');
        $productListingCmsSlot->setData($productListingStruct);

        $cmsBlock = new CmsBlockEntity();
        $cmsBlock->setUniqueIdentifier('1');
        $cmsBlock->setSlots(new CmsSlotCollection([$productListingCmsSlot]));

        $cmsSection = new CmsSectionEntity();
        $cmsSection->setUniqueIdentifier('1');
        $cmsSection->setBlocks(new CmsBlockCollection([$cmsBlock]));

        $cmsPage = new CmsPageEntity();
        $cmsPage->setUniqueIdentifier('1');
        $cmsPage->setSections(new CmsSectionCollection([$cmsSection]));

        $cmsPageCollection = new CmsPageCollection();
        $cmsPageCollection->add($cmsPage);

        yield [$productListingAggregationsExtractor, $cmsPageCollection];
    }

    public static function extractProductListingAggregationsWithCmsBlockWithoutSlotsProvider(): \Generator
    {
        $productListingAggregationsExtractor = new ProductListingAggregationsExtractor();

        $cmsPageCollection = new CmsPageCollection();
        $cmsPage = new CmsPageEntity();
        $cmsPage->setUniqueIdentifier('1');

        $cmsPageCollection->add($cmsPage);

        $cmsSection = new CmsSectionEntity();
        $cmsSection->setUniqueIdentifier('1');

        $cmsPage->setSections(new CmsSectionCollection([$cmsSection]));

        $cmsBlock = new CmsBlockEntity();
        $cmsBlock->setUniqueIdentifier('1');

        $cmsSection->setBlocks(new CmsBlockCollection([$cmsBlock]));

        yield [$productListingAggregationsExtractor, $cmsPageCollection];
    }

    public static function extractProductListingAggregationsWithCmsPageWithoutSectionsProvider(): \Generator
    {
        $productListingAggregationsExtractor = new ProductListingAggregationsExtractor();

        $cmsPageCollection = new CmsPageCollection();
        $cmsPage = new CmsPageEntity();
        $cmsPage->setUniqueIdentifier('1');

        $cmsPageCollection->add($cmsPage);

        yield [$productListingAggregationsExtractor, $cmsPageCollection];
    }

    public static function extractProductListingAggregationsWithCmsSectionWithoutBlocksProvider(): \Generator
    {
        $productListingAggregationsExtractor = new ProductListingAggregationsExtractor();

        $cmsPageCollection = new CmsPageCollection();
        $cmsPage = new CmsPageEntity();
        $cmsPage->setUniqueIdentifier('1');

        $cmsPageCollection->add($cmsPage);

        $cmsSection = new CmsSectionEntity();
        $cmsSection->setUniqueIdentifier('1');

        $cmsPage->setSections(new CmsSectionCollection([$cmsSection]));

        yield [$productListingAggregationsExtractor, $cmsPageCollection];
    }

    #[DataProvider('extractProductListingAggregationsProvider')]
    public function testExtractProductListingAggregations(
        ProductListingAggregationsExtractor $productListingAggregationsExtractor,
        CmsPageCollection $cmsPageCollection
    ): void {
        $aggregationResultCollection = $productListingAggregationsExtractor->extractProductListingAggregations($cmsPageCollection);
        $this->assertInstanceOf(AggregationResultCollection::class, $aggregationResultCollection);
    }

    #[DataProvider('extractProductListingAggregationsWithCmsBlockWithoutSlotsProvider')]
    public function testExtractProductListingAggregationsWithCmsBlockWithoutSlots(
        ProductListingAggregationsExtractor $productListingAggregationsExtractor,
        CmsPageCollection $cmsPageCollection
    ): void {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No product listing cms slots found');
        $productListingAggregationsExtractor->extractProductListingAggregations($cmsPageCollection);
    }

    #[DataProvider('extractProductListingAggregationsWithCmsPageWithoutSectionsProvider')]
    public function testExtractProductListingAggregationsWithCmsPageWithoutSections(
        ProductListingAggregationsExtractor $productListingAggregationsExtractor,
        CmsPageCollection $cmsPageCollection
    ): void {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No product listing cms slots found');
        $productListingAggregationsExtractor->extractProductListingAggregations($cmsPageCollection);
    }

    #[DataProvider('extractProductListingAggregationsWithCmsSectionWithoutBlocksProvider')]
    public function testExtractProductListingAggregationsWithCmsSectionWithoutBlocks(
        ProductListingAggregationsExtractor $productListingAggregationsExtractor,
        CmsPageCollection $cmsPageCollection
    ): void {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No product listing cms slots found');
        $productListingAggregationsExtractor->extractProductListingAggregations($cmsPageCollection);
    }
}
