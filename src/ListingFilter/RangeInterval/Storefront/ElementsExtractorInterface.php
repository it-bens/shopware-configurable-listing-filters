<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;

interface ElementsExtractorInterface
{
    /**
     * @return array<Element>
     */
    public function extractElementsFromAggregations(
        RangeIntervalListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): array;
}
