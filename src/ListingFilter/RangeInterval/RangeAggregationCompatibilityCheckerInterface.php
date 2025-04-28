<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval;

interface RangeAggregationCompatibilityCheckerInterface
{
    public function isDalFieldRangeAggregationCompatible(string $dalField): bool;
}
