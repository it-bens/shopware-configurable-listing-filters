<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\RangeAggregationCompatibilityCheckerInterface;
use RuntimeException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric\RangeResult;

final class ElementsExtractor implements ElementsExtractorInterface
{
    public function __construct(
        private readonly RangeAggregationCompatibilityCheckerInterface $rangeAggregationCompatibilityChecker,
        private readonly ElementsExtractorInterface $elementsExtractorForNonCompatibleFields,
        private readonly ElementBuilderInterface $elementBuilder,
    ) {
    }

    public function extractElementsFromAggregations(
        RangeIntervalListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): array {
        if (! $this->rangeAggregationCompatibilityChecker->isDalFieldRangeAggregationCompatible($configurationEntity->getDalField())) {
            return $this->elementsExtractorForNonCompatibleFields->extractElementsFromAggregations(
                $configurationEntity,
                $aggregationResults
            );
        }

        $aggregationResult = $aggregationResults->get($configurationEntity->getAggregationName());
        if (! $aggregationResult instanceof AggregationResult) {
            throw new RuntimeException(sprintf('The aggregation "%s" could not be found.', $configurationEntity->getAggregationName()));
        }

        if ($aggregationResult instanceof RangeResult === false) {
            throw new RuntimeException(
                sprintf('The aggregation result "%s" is not a range result.', $configurationEntity->getAggregationName())
            );
        }

        $intervals = [];
        foreach (array_keys($aggregationResult->getRanges()) as $intervalId) {
            $intervalEntity = $configurationEntity->getIntervals()?->get($intervalId);
            if ($intervalEntity !== null) {
                $intervals[] = $intervalEntity;
            }
        }

        $intervalCollection = new RangeIntervalListingFilterConfigurationIntervalCollection($intervals);
        $intervals = [];
        foreach ($intervalCollection as $intervalEntity) {
            $intervals[] = $this->elementBuilder->buildElement($intervalEntity);
        }

        return $intervals;
    }
}
