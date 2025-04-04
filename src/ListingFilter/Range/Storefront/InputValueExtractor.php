<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric\MaxResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric\MinResult;

final class InputValueExtractor implements InputValueExtractorInterface
{
    public function extractGteInputValueFromAggregations(
        RangeListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): ?int {
        $aggregation = $aggregationResults->get($configurationEntity->getMinimalValueAggregationName());
        if ($aggregation instanceof MinResult === false) {
            return null;
        }

        $gteInputValue = $aggregation->getMin();
        if (! is_numeric($gteInputValue)) {
            return null;
        }

        return (int) $gteInputValue;
    }

    public function extractLteInputValueFromAggregations(
        RangeListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): ?int {
        $aggregation = $aggregationResults->get($configurationEntity->getMaximalValueAggregationName());
        if ($aggregation instanceof MaxResult === false) {
            return null;
        }

        $lteInputValue = $aggregation->getMax();
        if (! is_numeric($lteInputValue)) {
            return null;
        }

        return (int) $lteInputValue;
    }
}
