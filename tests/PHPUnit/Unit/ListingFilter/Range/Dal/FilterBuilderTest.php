<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\Range\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\FilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\RequestValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Bucket\FilterAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Metric\MaxAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Metric\MinAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;

#[CoversClass(FilterBuilder::class)]
final class FilterBuilderTest extends TestCase
{
    public static function buildFilterProvider(): \Generator
    {
        $filterBuilder = new FilterBuilder();

        $filterConfiguration = new RangeListingFilterConfigurationEntity();
        $filterConfiguration->setDalField('price');

        yield 'filter with both min and max values' => [
            $filterBuilder,
            $filterConfiguration,
            new RequestValue(100, 500),
            $filterConfiguration->getFilterName(),
            true,
            $filterConfiguration->getMinimalValueAggregationName(),
            $filterConfiguration->getMaximalValueAggregationName(),
            $filterConfiguration->getFullyQualifiedDalField(),
            [
                'gte' => 100,
                'lte' => 500,
            ],
        ];

        $filterConfiguration = new RangeListingFilterConfigurationEntity();
        $filterConfiguration->setDalField('weight');

        yield 'filter with only min value' => [
            $filterBuilder,
            $filterConfiguration,
            new RequestValue(1000, null),
            $filterConfiguration->getFilterName(),
            true,
            $filterConfiguration->getMinimalValueAggregationName(),
            $filterConfiguration->getMaximalValueAggregationName(),
            $filterConfiguration->getFullyQualifiedDalField(),
            [
                'gte' => 1000,
            ],
        ];

        $filterConfiguration = new RangeListingFilterConfigurationEntity();
        $filterConfiguration->setDalField('length');

        yield 'filter with only max value' => [
            $filterBuilder,
            $filterConfiguration,
            new RequestValue(null, 2000),
            $filterConfiguration->getFilterName(),
            true,
            $filterConfiguration->getMinimalValueAggregationName(),
            $filterConfiguration->getMaximalValueAggregationName(),
            $filterConfiguration->getFullyQualifiedDalField(),
            [
                'lte' => 2000,
            ],
        ];

        $filterConfiguration = new RangeListingFilterConfigurationEntity();
        $filterConfiguration->setDalField('height');

        yield 'filter with no values' => [
            $filterBuilder,
            $filterConfiguration,
            new RequestValue(null, null),
            $filterConfiguration->getFilterName(),
            false,
            $filterConfiguration->getMinimalValueAggregationName(),
            $filterConfiguration->getMaximalValueAggregationName(),
            $filterConfiguration->getFullyQualifiedDalField(),
            [],
        ];
    }

    /**
     * @param array<string, int> $expectedRangeValues
     */
    #[DataProvider('buildFilterProvider')]
    public function testBuildFilter(
        FilterBuilder $filterBuilder,
        RangeListingFilterConfigurationEntity $filterConfiguration,
        RequestValue $requestValue,
        string $expectedName,
        bool $expectedIsFiltered,
        string $expectedMinAggregationName,
        string $expectedMaxAggregationName,
        string $expectedFullyQualifiedDalField,
        array $expectedRangeValues,
    ): void {
        $filter = $filterBuilder->buildFilter($filterConfiguration, $requestValue);

        $this->assertSame($expectedName, $filter->getName());
        $this->assertEquals($expectedIsFiltered, $filter->isFiltered());

        $aggregations = $filter->getAggregations();
        $this->assertCount(2, $aggregations);

        $minFilteredAggregation = null;
        $maxFilteredAggregation = null;

        foreach ($aggregations as $aggregation) {
            $this->assertInstanceOf(FilterAggregation::class, $aggregation);

            if ($aggregation->getName() === $expectedMinAggregationName) {
                $minFilteredAggregation = $aggregation;
            } elseif ($aggregation->getName() === $expectedMaxAggregationName) {
                $maxFilteredAggregation = $aggregation;
            }
        }

        $this->assertInstanceOf(FilterAggregation::class, $minFilteredAggregation, 'Min aggregation not found');
        $innerMinAggregation = $minFilteredAggregation->getAggregation();
        $this->assertInstanceOf(MinAggregation::class, $innerMinAggregation);
        $this->assertSame($expectedMinAggregationName, $innerMinAggregation->getName());
        $this->assertSame($expectedFullyQualifiedDalField, $innerMinAggregation->getField());

        $minFilters = $minFilteredAggregation->getFilter();
        $this->assertCount(1, $minFilters);
        $this->assertInstanceOf(NotFilter::class, $minFilters[0]);

        $this->assertInstanceOf(FilterAggregation::class, $maxFilteredAggregation, 'Max aggregation not found');
        $innerMaxAggregation = $maxFilteredAggregation->getAggregation();
        $this->assertInstanceOf(MaxAggregation::class, $innerMaxAggregation);
        $this->assertSame($expectedMaxAggregationName, $innerMaxAggregation->getName());
        $this->assertSame($expectedFullyQualifiedDalField, $innerMaxAggregation->getField());

        $maxFilters = $maxFilteredAggregation->getFilter();
        $this->assertCount(1, $maxFilters);
        $this->assertInstanceOf(NotFilter::class, $maxFilters[0]);

        $fieldFilter = $filter->getFilter();
        $this->assertInstanceOf(RangeFilter::class, $fieldFilter);
        $this->assertSame($expectedFullyQualifiedDalField, $fieldFilter->getField());

        $rangeValues = $filter->getValues();
        $this->assertEquals($expectedRangeValues, $rangeValues);
    }
}
