<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use RuntimeException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Bucket\Bucket;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Bucket\BucketResult;

final class ElementsExtractor implements ElementsExtractorInterface
{
    public function extractElementsFromAggregations(
        MultiSelectListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults
    ): array {
        $aggregationResult = $aggregationResults->get($configurationEntity->getAggregationName());
        if (! $aggregationResult instanceof AggregationResult) {
            throw new RuntimeException(sprintf('The aggregation "%s" could not be found.', $configurationEntity->getAggregationName()));
        }

        if ($aggregationResult instanceof BucketResult === false) {
            throw new RuntimeException(
                sprintf('The aggregation result "%s" is not a bucket result.', $configurationEntity->getAggregationName())
            );
        }

        $buckets = $aggregationResult->getBuckets();
        $buckets = array_filter($buckets, static fn (Bucket $bucket): bool => ($bucket->getKey() !== null && $bucket->getKey() !== ''));

        return array_values(array_map(static function (Bucket $bucket) use ($configurationEntity): Element {
            /** @var string $bucketKey */
            $bucketKey = $bucket->getKey();
            $elementText = $configurationEntity->getElementPrefix() . $bucketKey . $configurationEntity->getElementSuffix();

            return new Element($bucketKey, $elementText);
        }, $buckets));
    }
}
