<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service;

use RuntimeException;
use Shopware\Core\Content\Cms\CmsPageCollection;
use Shopware\Core\Content\Cms\SalesChannel\Struct\ProductListingStruct;
use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;

final class ProductListingAggregationsExtractor implements ProductListingAggregationsExtractorInterface
{
    public function extractProductListingAggregations(CmsPageCollection $cmsPageCollection): AggregationResultCollection
    {
        $productListingCmsSlots = [];
        foreach ($cmsPageCollection->getElements() as $cmsPage) {
            if ($cmsPage->getSections() === null) {
                continue;
            }

            foreach ($cmsPage->getSections() as $cmsSection) {
                if ($cmsSection->getBlocks() === null) {
                    continue;
                }

                foreach ($cmsSection->getBlocks() as $cmsBlock) {
                    if ($cmsBlock->getSlots() === null) {
                        continue;
                    }

                    foreach ($cmsBlock->getSlots() as $cmsSlot) {
                        if ($cmsSlot->getType() === 'product-listing') {
                            $productListingCmsSlots[] = $cmsSlot;
                        }
                    }
                }
            }
        }

        if ($productListingCmsSlots === []) {
            throw new RuntimeException('No product listing cms slots found');
        }

        $productListingCmsSlot = $productListingCmsSlots[0];
        /** @var ProductListingStruct $productListingStruct */
        $productListingStruct = $productListingCmsSlot->getData();
        /** @var ProductListingResult $productListingResult */
        $productListingResult = $productListingStruct->getListing();

        return $productListingResult->getAggregations();
    }
}
