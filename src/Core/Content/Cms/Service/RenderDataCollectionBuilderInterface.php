<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Core\Content\Cms\Service;

use ITB\ITBConfigurableListingFilters\Core\Content\Cms\RenderDataCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;

interface RenderDataCollectionBuilderInterface
{
    public function buildRenderDataCollection(
        ListingFilterConfigurationCollection $listingFilterConfigurationCollection,
        AggregationResultCollection $aggregationResults
    ): RenderDataCollection;
}
