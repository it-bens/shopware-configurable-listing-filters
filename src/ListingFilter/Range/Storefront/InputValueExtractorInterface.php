<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;

interface InputValueExtractorInterface
{
    public function extractGteInputValueFromAggregations(
        RangeListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): ?int;

    public function extractLteInputValueFromAggregations(
        RangeListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): ?int;
}
