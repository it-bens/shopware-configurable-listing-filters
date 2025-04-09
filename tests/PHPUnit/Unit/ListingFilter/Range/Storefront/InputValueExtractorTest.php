<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\Range\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\InputValueExtractor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric\MaxResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric\MinResult;

#[CoversClass(InputValueExtractor::class)]
final class InputValueExtractorTest extends TestCase
{
    public static function extractGteInputValueFromAggregationsProvider(): \Generator
    {
        $inputValueExtractor = new InputValueExtractor();

        // Test case with valid MinResult
        $configEntity = new RangeListingFilterConfigurationEntity();
        $configEntity->setDalField('price');

        $minAggregationName = $configEntity->getMinimalValueAggregationName();

        $minResult = new MinResult($minAggregationName, 100);
        $aggregationResults = new AggregationResultCollection();
        $aggregationResults->add($minResult);

        yield 'valid MinResult' => [$inputValueExtractor, $configEntity, $aggregationResults, 100];

        // Test case with non-numeric min value
        $configEntity = new RangeListingFilterConfigurationEntity();
        $configEntity->setDalField('price');

        $minAggregationName = $configEntity->getMinimalValueAggregationName();

        $minResult = new MinResult($minAggregationName, 'non-numeric');
        $aggregationResults = new AggregationResultCollection();
        $aggregationResults->add($minResult);

        yield 'non-numeric min value' => [$inputValueExtractor, $configEntity, $aggregationResults, null];

        // Test case with missing aggregation
        $configEntity = new RangeListingFilterConfigurationEntity();
        $configEntity->setDalField('price');

        $aggregationResults = new AggregationResultCollection();

        yield 'missing aggregation' => [$inputValueExtractor, $configEntity, $aggregationResults, null];

        // Test case with wrong aggregation type
        $configEntity = new RangeListingFilterConfigurationEntity();
        $configEntity->setDalField('price');

        $minAggregationName = $configEntity->getMinimalValueAggregationName();

        // Using MaxResult instead of MinResult
        $wrongResult = new MaxResult($minAggregationName, 100);
        $aggregationResults = new AggregationResultCollection();
        $aggregationResults->add($wrongResult);

        yield 'wrong aggregation type' => [$inputValueExtractor, $configEntity, $aggregationResults, null];
    }

    public static function extractLteInputValueFromAggregationsProvider(): \Generator
    {
        $inputValueExtractor = new InputValueExtractor();

        // Test case with valid MaxResult
        $configEntity = new RangeListingFilterConfigurationEntity();
        $configEntity->setDalField('price');

        $maxAggregationName = $configEntity->getMaximalValueAggregationName();

        $maxResult = new MaxResult($maxAggregationName, 500);
        $aggregationResults = new AggregationResultCollection();
        $aggregationResults->add($maxResult);

        yield 'valid MaxResult' => [$inputValueExtractor, $configEntity, $aggregationResults, 500];

        // Test case with non-numeric max value
        $configEntity = new RangeListingFilterConfigurationEntity();
        $configEntity->setDalField('price');

        $maxAggregationName = $configEntity->getMaximalValueAggregationName();

        $maxResult = new MaxResult($maxAggregationName, 'non-numeric');
        $aggregationResults = new AggregationResultCollection();
        $aggregationResults->add($maxResult);

        yield 'non-numeric max value' => [$inputValueExtractor, $configEntity, $aggregationResults, null];

        // Test case with missing aggregation
        $configEntity = new RangeListingFilterConfigurationEntity();
        $configEntity->setDalField('price');

        $aggregationResults = new AggregationResultCollection();

        yield 'missing aggregation' => [$inputValueExtractor, $configEntity, $aggregationResults, null];

        // Test case with wrong aggregation type
        $configEntity = new RangeListingFilterConfigurationEntity();
        $configEntity->setDalField('price');

        $maxAggregationName = $configEntity->getMaximalValueAggregationName();

        // Using MinResult instead of MaxResult
        $wrongResult = new MinResult($maxAggregationName, 500);
        $aggregationResults = new AggregationResultCollection();
        $aggregationResults->add($wrongResult);

        yield 'wrong aggregation type' => [$inputValueExtractor, $configEntity, $aggregationResults, null];
    }

    #[DataProvider('extractGteInputValueFromAggregationsProvider')]
    public function testExtractGteInputValueFromAggregations(
        InputValueExtractor $inputValueExtractor,
        RangeListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults,
        ?int $expectedValue
    ): void {
        $result = $inputValueExtractor->extractGteInputValueFromAggregations($configurationEntity, $aggregationResults);
        $this->assertEquals($expectedValue, $result);
    }

    #[DataProvider('extractLteInputValueFromAggregationsProvider')]
    public function testExtractLteInputValueFromAggregations(
        InputValueExtractor $inputValueExtractor,
        RangeListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults,
        ?int $expectedValue
    ): void {
        $result = $inputValueExtractor->extractLteInputValueFromAggregations($configurationEntity, $aggregationResults);
        $this->assertEquals($expectedValue, $result);
    }
}
