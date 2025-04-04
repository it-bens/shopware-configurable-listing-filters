<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service;

use Shopware\Core\Content\Cms\CmsPageCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;

interface ProductListingAggregationsExtractorInterface
{
    public function extractProductListingAggregations(CmsPageCollection $cmsPageCollection): AggregationResultCollection;
}
