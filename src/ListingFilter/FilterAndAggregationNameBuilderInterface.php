<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter;

interface FilterAndAggregationNameBuilderInterface
{
    public function buildFilterAndAggregationName(string $dalField): string;
}
