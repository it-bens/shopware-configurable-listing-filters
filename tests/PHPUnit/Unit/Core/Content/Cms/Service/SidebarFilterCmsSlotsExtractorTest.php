<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\Cms\Service;

use ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service\SidebarFilterCmsSlotsExtractor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Cms\Aggregate\CmsBlock\CmsBlockCollection;
use Shopware\Core\Content\Cms\Aggregate\CmsBlock\CmsBlockEntity;
use Shopware\Core\Content\Cms\Aggregate\CmsSection\CmsSectionCollection;
use Shopware\Core\Content\Cms\Aggregate\CmsSection\CmsSectionEntity;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotCollection;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\CmsPageCollection;
use Shopware\Core\Content\Cms\CmsPageEntity;

final class SidebarFilterCmsSlotsExtractorTest extends TestCase
{
    public static function extractSidebarFilterCmsSlotsProvider(): \Generator
    {
        $sidebarFilterCmsSlotsExtractor = new SidebarFilterCmsSlotsExtractor();

        $cmsPage = new CmsPageEntity();
        $cmsPage->setUniqueIdentifier('1');

        $cmsPageCollection = new CmsPageCollection();
        $cmsPageCollection->add($cmsPage);

        yield 'no cms sections' => [$sidebarFilterCmsSlotsExtractor, $cmsPageCollection, 0];

        $cmsSection = new CmsSectionEntity();
        $cmsSection->setUniqueIdentifier('1');

        $cmsPage = clone $cmsPage;
        $cmsPage->setSections(new CmsSectionCollection([$cmsSection]));

        $cmsPageCollection = clone $cmsPageCollection;
        $cmsPageCollection->add($cmsPage);

        yield 'no cms blocks' => [$sidebarFilterCmsSlotsExtractor, $cmsPageCollection, 0];

        $cmsBlock = new CmsBlockEntity();
        $cmsBlock->setUniqueIdentifier('1');

        $cmsSection = clone $cmsSection;
        $cmsSection->setBlocks(new CmsBlockCollection([$cmsBlock]));

        $cmsPage = clone $cmsPage;
        $cmsPage->setSections(new CmsSectionCollection([$cmsSection]));

        $cmsPageCollection = clone $cmsPageCollection;
        $cmsPageCollection->add($cmsPage);

        yield 'no cms slots' => [$sidebarFilterCmsSlotsExtractor, $cmsPageCollection, 0];

        $productListingCmsSlot = new CmsSlotEntity();
        $productListingCmsSlot->setUniqueIdentifier('1');
        $productListingCmsSlot->setSlot('dont-care');
        $productListingCmsSlot->setType('sidebar-filter');

        $cmsBlock = clone $cmsBlock;
        $cmsBlock->setSlots(new CmsSlotCollection([$productListingCmsSlot]));

        $cmsSection = clone $cmsSection;
        $cmsSection->setBlocks(new CmsBlockCollection([$cmsBlock]));

        $cmsPage = clone $cmsPage;
        $cmsPage->setSections(new CmsSectionCollection([$cmsSection]));

        $cmsPageCollection = new CmsPageCollection();
        $cmsPageCollection->add($cmsPage);

        yield 'one cms slot that matches' => [$sidebarFilterCmsSlotsExtractor, $cmsPageCollection, 1];

        $productListingCmsSlot = new CmsSlotEntity();
        $productListingCmsSlot->setUniqueIdentifier('1');
        $productListingCmsSlot->setSlot('dont-care');
        $productListingCmsSlot->setType('product-listing');

        $cmsBlock = clone $cmsBlock;
        $cmsBlock->setSlots(new CmsSlotCollection([$productListingCmsSlot]));

        $cmsSection = clone $cmsSection;
        $cmsSection->setBlocks(new CmsBlockCollection([$cmsBlock]));

        $cmsPage = clone $cmsPage;
        $cmsPage->setSections(new CmsSectionCollection([$cmsSection]));

        $cmsPageCollection = new CmsPageCollection();
        $cmsPageCollection->add($cmsPage);

        yield 'one cms slot that does not match' => [$sidebarFilterCmsSlotsExtractor, $cmsPageCollection, 0];
    }

    #[DataProvider('extractSidebarFilterCmsSlotsProvider')]
    public function testExtractSidebarFilterCmsSlots(
        SidebarFilterCmsSlotsExtractor $sidebarFilterCmsSlotsExtractor,
        CmsPageCollection $cmsPageCollection,
        int $expectedCmsSlotCount
    ): void {
        $sidebarFilterCmsSlots = $sidebarFilterCmsSlotsExtractor->extractSidebarFilterCmsSlots($cmsPageCollection);
        $this->assertCount($expectedCmsSlotCount, $sidebarFilterCmsSlots);
        $this->assertContainsOnlyInstancesOf(CmsSlotEntity::class, $sidebarFilterCmsSlots);
    }
}
