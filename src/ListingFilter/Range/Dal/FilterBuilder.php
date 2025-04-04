<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Bucket\FilterAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Metric\MaxAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Metric\MinAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;

final class FilterBuilder implements FilterBuilderInterface
{
    public function buildFilter(RangeListingFilterConfigurationEntity $configurationEntity, RequestValue $requestValue): Filter
    {
        $minAggregation = new MinAggregation(
            $configurationEntity->getMinimalValueAggregationName(),
            $configurationEntity->getFullyQualifiedDalField()
        );
        $filteredMinAggregation = new FilterAggregation($configurationEntity->getMinimalValueAggregationName(), $minAggregation, [
            new NotFilter(MultiFilter::CONNECTION_AND, [new EqualsFilter($configurationEntity->getFullyQualifiedDalField(), null)]),
        ]);

        $maxAggregation = new MaxAggregation(
            $configurationEntity->getMaximalValueAggregationName(),
            $configurationEntity->getFullyQualifiedDalField()
        );
        $filteredMaxAggregation = new FilterAggregation($configurationEntity->getMaximalValueAggregationName(), $maxAggregation, [
            new NotFilter(MultiFilter::CONNECTION_AND, [new EqualsFilter($configurationEntity->getFullyQualifiedDalField(), null)]),
        ]);

        return new Filter(
            $configurationEntity->getFilterName(),
            $requestValue->isFiltered(),
            [$filteredMinAggregation, $filteredMaxAggregation],
            new RangeFilter($configurationEntity->getFullyQualifiedDalField(), $requestValue->range()),
            $requestValue->range()
        );
    }
}
