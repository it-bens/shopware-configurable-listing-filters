<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;
use RuntimeException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric\CountResult;

final class ElementsExtractorForNonCompatibleFields implements ElementsExtractorInterface
{
    public function extractElementsFromAggregations(
        RangeIntervalListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): iterable {
        $intervalCollection = $configurationEntity->getIntervals();
        if (! $intervalCollection instanceof RangeIntervalListingFilterConfigurationIntervalCollection) {
            throw new \RuntimeException('`intervals` not loaded in `RangeIntervalListingFilterConfigurationEntity`');
        }

        $intervals = [];
        foreach ($intervalCollection as $intervalEntity) {
            $aggregationResult = $aggregationResults->get($intervalEntity->getCountAggregationName());
            if (! $aggregationResult instanceof AggregationResult) {
                throw new RuntimeException(sprintf('The aggregation "%s" could not be found.', $intervalEntity->getCountAggregationName()));
            }

            if ($aggregationResult instanceof CountResult === false) {
                throw new RuntimeException(
                    sprintf('The aggregation result "%s" is not a count result.', $intervalEntity->getCountAggregationName())
                );
            }

            if ($aggregationResult->getCount() === 0) {
                continue;
            }

            $intervalId = $intervalEntity->getIdFromCountAggregationName($aggregationResult->getName());
            $intervals[] = $intervalCollection->get($intervalId);
        }

        $intervalCollection = new RangeIntervalListingFilterConfigurationIntervalCollection(array_filter($intervals));
        foreach ($intervalCollection as $intervalEntity) {
            $minText = $intervalEntity->getMin() !== null ? (string) $intervalEntity->getMin() : 'test';
            $maxText = $intervalEntity->getMax() !== null ? (string) $intervalEntity->getMax() : 'test';

            yield new Element($intervalEntity->getId(), $minText . ' - ' . $maxText);
        }
    }
}
