<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter;

final class FilterAndAggregationNameBuilder implements FilterAndAggregationNameBuilderInterface
{
    public function buildFilterAndAggregationName(string $dalField): string
    {
        $snakeCasedFilterField = strtolower((string) preg_replace('/[A-Z]/', '_\\0', lcfirst($dalField)));

        return str_replace(['.', '_'], '-', $snakeCasedFilterField);
    }
}
