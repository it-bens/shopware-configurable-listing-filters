<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\RangeAggregationCompatibilityCheckerInterface;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Bucket\FilterAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Metric\RangeAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;

final class FilterBuilder implements FilterBuilderInterface
{
    public function __construct(
        private readonly RangeAggregationCompatibilityCheckerInterface $rangeAggregationCompatibilityChecker,
        private readonly FilterBuilderInterface $filterBuilderForNonCompatibleFields,
    ) {
    }

    public function buildFilter(RangeIntervalListingFilterConfigurationEntity $configurationEntity, RequestValue $requestValue): Filter
    {
        if (! $this->rangeAggregationCompatibilityChecker->isDalFieldRangeAggregationCompatible($configurationEntity->getDalField())) {
            return $this->filterBuilderForNonCompatibleFields->buildFilter($configurationEntity, $requestValue);
        }

        $intervalCollection = $configurationEntity->getIntervals();
        if (! $intervalCollection instanceof RangeIntervalListingFilterConfigurationIntervalCollection) {
            throw new \RuntimeException('`intervals` not loaded in `RangeIntervalListingFilterConfigurationEntity`');
        }

        $ranges = [];
        foreach ($intervalCollection as $interval) {
            $ranges[] = [
                'from' => in_array($interval->getMin(), [null, 0], true) ? null : (float) $interval->getMin(),
                'to' => in_array($interval->getMax(), [null, 0], true) ? null : (float) $interval->getMax(),
                'key' => $interval->getId(),
            ];
        }

        $aggregation = new RangeAggregation(
            $configurationEntity->getAggregationName(),
            $configurationEntity->getFullyQualifiedDalField(),
            $ranges
        );
        $filteredAggregation = new FilterAggregation($configurationEntity->getAggregationName(), $aggregation, [
            new NotFilter(MultiFilter::CONNECTION_AND, [new EqualsFilter($configurationEntity->getFullyQualifiedDalField(), null)]),
        ]);

        return new Filter(
            $configurationEntity->getFilterName(),
            $requestValue->isFiltered(),
            [$filteredAggregation],
            new RangeFilter($configurationEntity->getFullyQualifiedDalField(), $requestValue->range()),
            $requestValue->range()
        );
    }
}
