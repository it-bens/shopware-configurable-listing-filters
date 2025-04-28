<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use RuntimeException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric\CountResult;

final class ElementsExtractorForNonCompatibleFields implements ElementsExtractorInterface
{
    public function __construct(
        private readonly ElementBuilderInterface $elementBuilder,
    ) {
    }

    public function extractElementsFromAggregations(
        RangeIntervalListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): array {
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
        $intervals = [];
        foreach ($intervalCollection as $intervalEntity) {
            $intervals[] = $this->elementBuilder->buildElement($intervalEntity);
        }

        return $intervals;
    }
}
