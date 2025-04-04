<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Metric\MaxAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

final class FilterBuilder implements FilterBuilderInterface
{
    public function buildFilter(CheckboxListingFilterConfigurationEntity $filterConfiguration, RequestValue $requestValue): Filter
    {
        $maxAggregation = new MaxAggregation($filterConfiguration->getAggregationName(), $filterConfiguration->getFullyQualifiedDalField());

        return new Filter(
            $filterConfiguration->getFilterName(),
            $requestValue->isFiltered(),
            [$maxAggregation],
            new EqualsFilter($filterConfiguration->getFullyQualifiedDalField(), $requestValue->value),
            $requestValue->value
        );
    }
}
