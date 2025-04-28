<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Bucket\FilterAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Metric\CountAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;

final class FilterBuilderForNonCompatibleFields implements FilterBuilderInterface
{
    public function buildFilter(RangeIntervalListingFilterConfigurationEntity $configurationEntity, RequestValue $requestValue): Filter
    {
        $intervalCollection = $configurationEntity->getIntervals();
        if (! $intervalCollection instanceof RangeIntervalListingFilterConfigurationIntervalCollection) {
            throw new \RuntimeException('`intervals` not loaded in `RangeIntervalListingFilterConfigurationEntity`');
        }

        $aggregations = [];
        foreach ($intervalCollection as $intervalEntity) {
            $aggregation = new CountAggregation(
                $intervalEntity->getCountAggregationName(),
                $configurationEntity->getFullyQualifiedDalField()
            );
            $aggregationFilter = new RangeFilter($configurationEntity->getFullyQualifiedDalField(), $intervalEntity->getRangeForFilter());

            $aggregations[] = new FilterAggregation($intervalEntity->getCountAggregationName(), $aggregation, [$aggregationFilter]);
        }

        return new Filter(
            $configurationEntity->getFilterName(),
            $requestValue->isFiltered(),
            $aggregations,
            new RangeFilter($configurationEntity->getFullyQualifiedDalField(), $requestValue->range()),
            $requestValue->range()
        );
    }
}
