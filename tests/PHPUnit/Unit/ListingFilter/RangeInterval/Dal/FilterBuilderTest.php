<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\RangeInterval\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\FilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\FilterBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\RequestValue;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\RangeAggregationCompatibilityCheckerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Bucket\FilterAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Metric\RangeAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;
use Shopware\Core\Framework\Uuid\Uuid;

#[CoversClass(FilterBuilder::class)]
final class FilterBuilderTest extends TestCase
{
    private FilterBuilder $filterBuilder;

    private MockObject&FilterBuilderInterface $filterBuilderForNonCompatibleFieldsMock;

    private MockObject&RangeAggregationCompatibilityCheckerInterface $rangeAggregationCompatibilityCheckerMock;

    protected function setUp(): void
    {
        $this->rangeAggregationCompatibilityCheckerMock = $this->createMock(RangeAggregationCompatibilityCheckerInterface::class);
        $this->filterBuilderForNonCompatibleFieldsMock = $this->createMock(FilterBuilderInterface::class);
        $this->filterBuilder = new FilterBuilder(
            $this->rangeAggregationCompatibilityCheckerMock,
            $this->filterBuilderForNonCompatibleFieldsMock
        );
    }

    public static function buildFilterWithCompatibleFieldFilteredClosedRangeProvider(): \Generator
    {
        $config = new RangeIntervalListingFilterConfigurationEntity();
        $config->setId(Uuid::randomHex());
        $config->setUniqueName(Uuid::randomHex());
        $config->setDalField('price.gross');
        $config->setPosition(10);

        $interval1 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval1->setId(Uuid::randomHex());
        $interval1->setMin(0);
        $interval1->setMax(100);
        $interval1->setPosition(10);

        $interval2 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval2->setId(Uuid::randomHex());
        $interval2->setMin(101);
        $interval2->setMax(500);
        $interval2->setPosition(20);

        $interval3 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval3->setId(Uuid::randomHex());
        $interval3->setMin(501);
        $interval3->setMax(null);
        $interval3->setPosition(30);

        $intervals = new RangeIntervalListingFilterConfigurationIntervalCollection([$interval1, $interval2, $interval3]);
        $config->setIntervals($intervals);

        $requestValueSingle = new RequestValue([$interval2]);
        yield 'compatible field, single selection' => [
            $config,
            $requestValueSingle,
            [
                RangeFilter::GTE => 101.0,
                RangeFilter::LTE => 500.0,
            ],
            [
                [
                    'from' => null,
                    'to' => 100.0,
                    'key' => $interval1->getId(),
                ],
                [
                    'from' => 101.0,
                    'to' => 500.0,
                    'key' => $interval2->getId(),
                ],
                [
                    'from' => 501.0,
                    'to' => null,
                    'key' => $interval3->getId(),
                ],
            ],
        ];

        $requestValueMultipleClosed = new RequestValue([$interval1, $interval2]);
        yield 'compatible field, multiple selection closed end' => [
            $config,
            $requestValueMultipleClosed,
            [
                RangeFilter::GTE => 0.0,
                RangeFilter::LTE => 500.0,
            ],
            [
                [
                    'from' => null,
                    'to' => 100.0,
                    'key' => $interval1->getId(),
                ],
                [
                    'from' => 101.0,
                    'to' => 500.0,
                    'key' => $interval2->getId(),
                ],
                [
                    'from' => 501.0,
                    'to' => null,
                    'key' => $interval3->getId(),
                ],
            ],
        ];
    }

    public static function buildFilterWithCompatibleFieldFilteredOpenEndedRangeProvider(): \Generator
    {
        $config = new RangeIntervalListingFilterConfigurationEntity();
        $config->setId(Uuid::randomHex());
        $config->setUniqueName(Uuid::randomHex());
        $config->setDalField('price.gross');
        $config->setPosition(10);

        $interval1 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval1->setId(Uuid::randomHex());
        $interval1->setMin(0);
        $interval1->setMax(100);
        $interval1->setPosition(10);

        $interval2 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval2->setId(Uuid::randomHex());
        $interval2->setMin(101);
        $interval2->setMax(500);
        $interval2->setPosition(20);

        $interval3 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval3->setId(Uuid::randomHex());
        $interval3->setMin(501);
        $interval3->setMax(null);
        $interval3->setPosition(30);

        $intervals = new RangeIntervalListingFilterConfigurationIntervalCollection([$interval1, $interval2, $interval3]);
        $config->setIntervals($intervals);

        $requestValueMultiple = new RequestValue([$interval1, $interval3]);
        yield 'compatible field, multiple selection including open end' => [
            $config,
            $requestValueMultiple,
            [
                RangeFilter::GTE => 0.0,
            ],
            [
                RangeFilter::GTE => 0.0,
            ],
            [
                [
                    'from' => null,
                    'to' => 100.0,
                    'key' => $interval1->getId(),
                ],
                [
                    'from' => 101.0,
                    'to' => 500.0,
                    'key' => $interval2->getId(),
                ],
                [
                    'from' => 501.0,
                    'to' => null,
                    'key' => $interval3->getId(),
                ],
            ],
        ];
    }

    public static function buildFilterWithCompatibleFieldNotFilteredProvider(): \Generator
    {
        $config = new RangeIntervalListingFilterConfigurationEntity();
        $config->setId(Uuid::randomHex());
        $config->setUniqueName(Uuid::randomHex());
        $config->setDalField('price.gross');
        $config->setPosition(10);

        $interval1 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval1->setId(Uuid::randomHex());
        $interval1->setMin(0);
        $interval1->setMax(100);
        $interval1->setPosition(10);

        $interval2 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval2->setId(Uuid::randomHex());
        $interval2->setMin(101);
        $interval2->setMax(500);
        $interval2->setPosition(20);

        $interval3 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval3->setId(Uuid::randomHex());
        $interval3->setMin(501);
        $interval3->setMax(null);
        $interval3->setPosition(30);

        $intervals = new RangeIntervalListingFilterConfigurationIntervalCollection([$interval1, $interval2, $interval3]);
        $config->setIntervals($intervals);

        $requestValueEmpty = new RequestValue([]);
        yield 'compatible field, no selection' => [
            $config,
            $requestValueEmpty,
            [],
            [
                [
                    'from' => null,
                    'to' => 100.0,
                    'key' => $interval1->getId(),
                ],
                [
                    'from' => 101.0,
                    'to' => 500.0,
                    'key' => $interval2->getId(),
                ],
                [
                    'from' => 501.0,
                    'to' => null,
                    'key' => $interval3->getId(),
                ],
            ],
        ];
    }

    public function testBuildFilterThrowsExceptionWhenIntervalsNotLoaded(): void
    {
        $config = new RangeIntervalListingFilterConfigurationEntity();
        $config->setId(Uuid::randomHex());
        $config->setUniqueName(Uuid::randomHex());
        $config->setDalField('price.gross');
        $config->setPosition(20);

        $requestValue = new RequestValue([]);

        $this->rangeAggregationCompatibilityCheckerMock
            ->expects($this->once())
            ->method('isDalFieldRangeAggregationCompatible')
            ->with($config->getDalField())
            ->willReturn(true);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('`intervals` not loaded in `RangeIntervalListingFilterConfigurationEntity`');

        $this->filterBuilder->buildFilter($config, $requestValue);
    }

    /**
     * @param array<string, mixed> $expectedFilterValues
     * @param array<array<string, mixed>> $expectedAggregationRanges
     */
    #[DataProvider('buildFilterWithCompatibleFieldFilteredClosedRangeProvider')]
    public function testBuildFilterWithCompatibleFieldFilteredClosedRange(
        RangeIntervalListingFilterConfigurationEntity $configurationEntity,
        RequestValue $requestValue,
        array $expectedFilterValues,
        array $expectedAggregationRanges
    ): void {
        $this->rangeAggregationCompatibilityCheckerMock
            ->expects($this->once())
            ->method('isDalFieldRangeAggregationCompatible')
            ->with($configurationEntity->getDalField())
            ->willReturn(true);

        $this->filterBuilderForNonCompatibleFieldsMock
            ->expects($this->never())
            ->method('buildFilter');

        $filter = $this->filterBuilder->buildFilter($configurationEntity, $requestValue);

        $this->assertInstanceOf(Filter::class, $filter);
        $this->assertSame($configurationEntity->getFilterName(), $filter->getName());
        $this->assertTrue($filter->isFiltered());
        $this->assertEquals($expectedFilterValues, $filter->getValues());

        $aggregations = $filter->getAggregations();
        $this->assertCount(1, $aggregations);
        $filteredAggregation = array_values($aggregations)[0];
        $this->assertInstanceOf(FilterAggregation::class, $filteredAggregation);
        $this->assertSame($configurationEntity->getAggregationName(), $filteredAggregation->getName());

        $innerAggregation = $filteredAggregation->getAggregation();
        $this->assertInstanceOf(RangeAggregation::class, $innerAggregation);
        $this->assertSame($configurationEntity->getAggregationName(), $innerAggregation->getName());
        $this->assertSame($configurationEntity->getFullyQualifiedDalField(), $innerAggregation->getField());
        $this->assertEquals($expectedAggregationRanges, $innerAggregation->getRanges());

        $aggregationFilters = $filteredAggregation->getFilter();
        $this->assertCount(1, $aggregationFilters);
        $notNullFilter = array_values($aggregationFilters)[0];
        $this->assertInstanceOf(NotFilter::class, $notNullFilter);
        $this->assertSame(MultiFilter::CONNECTION_AND, $notNullFilter->getOperator());
        $this->assertCount(1, $notNullFilter->getQueries());
        $equalsNullFilter = $notNullFilter->getQueries()[0];
        $this->assertInstanceOf(EqualsFilter::class, $equalsNullFilter);
        $this->assertSame($configurationEntity->getFullyQualifiedDalField(), $equalsNullFilter->getField());
        $this->assertNull($equalsNullFilter->getValue());

        $mainFilter = $filter->getFilter();
        $this->assertInstanceOf(RangeFilter::class, $mainFilter);
        $this->assertSame($configurationEntity->getFullyQualifiedDalField(), $mainFilter->getField());
        $this->assertEquals($expectedFilterValues, $mainFilter->getParameters());
    }

    /**
     * @param array<string, mixed> $expectedFilterValues
     * @param array<string, mixed> $expectedMainFilterParams
     * @param array<array<string, mixed>> $expectedAggregationRanges
     */
    #[DataProvider('buildFilterWithCompatibleFieldFilteredOpenEndedRangeProvider')]
    public function testBuildFilterWithCompatibleFieldFilteredOpenEndedRange(
        RangeIntervalListingFilterConfigurationEntity $configurationEntity,
        RequestValue $requestValue,
        array $expectedFilterValues,
        array $expectedMainFilterParams,
        array $expectedAggregationRanges
    ): void {
        $this->rangeAggregationCompatibilityCheckerMock
            ->expects($this->once())
            ->method('isDalFieldRangeAggregationCompatible')
            ->with($configurationEntity->getDalField())
            ->willReturn(true);

        $this->filterBuilderForNonCompatibleFieldsMock
            ->expects($this->never())
            ->method('buildFilter');

        $filter = $this->filterBuilder->buildFilter($configurationEntity, $requestValue);

        $this->assertInstanceOf(Filter::class, $filter);
        $this->assertSame($configurationEntity->getFilterName(), $filter->getName());
        $this->assertTrue($filter->isFiltered());
        $this->assertEquals($expectedFilterValues, $filter->getValues());

        $aggregations = $filter->getAggregations();
        $this->assertCount(1, $aggregations);
        $filteredAggregation = array_values($aggregations)[0];
        $this->assertInstanceOf(FilterAggregation::class, $filteredAggregation);
        $this->assertSame($configurationEntity->getAggregationName(), $filteredAggregation->getName());

        $innerAggregation = $filteredAggregation->getAggregation();
        $this->assertInstanceOf(RangeAggregation::class, $innerAggregation);
        $this->assertSame($configurationEntity->getAggregationName(), $innerAggregation->getName());
        $this->assertSame($configurationEntity->getFullyQualifiedDalField(), $innerAggregation->getField());
        $this->assertEquals($expectedAggregationRanges, $innerAggregation->getRanges());

        $aggregationFilters = $filteredAggregation->getFilter();
        $this->assertCount(1, $aggregationFilters);
        $notNullFilter = array_values($aggregationFilters)[0];
        $this->assertInstanceOf(NotFilter::class, $notNullFilter);
        $this->assertSame(MultiFilter::CONNECTION_AND, $notNullFilter->getOperator());
        $this->assertCount(1, $notNullFilter->getQueries());
        $equalsNullFilter = $notNullFilter->getQueries()[0];
        $this->assertInstanceOf(EqualsFilter::class, $equalsNullFilter);
        $this->assertSame($configurationEntity->getFullyQualifiedDalField(), $equalsNullFilter->getField());
        $this->assertNull($equalsNullFilter->getValue());

        $mainFilter = $filter->getFilter();
        $this->assertInstanceOf(RangeFilter::class, $mainFilter);
        $this->assertSame($configurationEntity->getFullyQualifiedDalField(), $mainFilter->getField());
        $this->assertEquals($expectedMainFilterParams, $mainFilter->getParameters());
    }

    /**
     * @param array<string, mixed> $expectedFilterValues
     * @param array<array<string, mixed>> $expectedAggregationRanges
     */
    #[DataProvider('buildFilterWithCompatibleFieldNotFilteredProvider')]
    public function testBuildFilterWithCompatibleFieldNotFiltered(
        RangeIntervalListingFilterConfigurationEntity $configurationEntity,
        RequestValue $requestValue,
        array $expectedFilterValues,
        array $expectedAggregationRanges
    ): void {
        $this->rangeAggregationCompatibilityCheckerMock
            ->expects($this->once())
            ->method('isDalFieldRangeAggregationCompatible')
            ->with($configurationEntity->getDalField())
            ->willReturn(true);

        $this->filterBuilderForNonCompatibleFieldsMock
            ->expects($this->never())
            ->method('buildFilter');

        $filter = $this->filterBuilder->buildFilter($configurationEntity, $requestValue);

        $this->assertInstanceOf(Filter::class, $filter);
        $this->assertSame($configurationEntity->getFilterName(), $filter->getName());
        $this->assertFalse($filter->isFiltered());
        $this->assertEquals($expectedFilterValues, $filter->getValues());

        $aggregations = $filter->getAggregations();
        $this->assertCount(1, $aggregations);
        $filteredAggregation = array_values($aggregations)[0];
        $this->assertInstanceOf(FilterAggregation::class, $filteredAggregation);
        $this->assertSame($configurationEntity->getAggregationName(), $filteredAggregation->getName());

        $innerAggregation = $filteredAggregation->getAggregation();
        $this->assertInstanceOf(RangeAggregation::class, $innerAggregation);
        $this->assertSame($configurationEntity->getAggregationName(), $innerAggregation->getName());
        $this->assertSame($configurationEntity->getFullyQualifiedDalField(), $innerAggregation->getField());
        $this->assertEquals($expectedAggregationRanges, $innerAggregation->getRanges());

        $aggregationFilters = $filteredAggregation->getFilter();
        $this->assertCount(1, $aggregationFilters);
        $notNullFilter = array_values($aggregationFilters)[0];
        $this->assertInstanceOf(NotFilter::class, $notNullFilter);
        $this->assertSame(MultiFilter::CONNECTION_AND, $notNullFilter->getOperator());
        $this->assertCount(1, $notNullFilter->getQueries());
        $equalsNullFilter = $notNullFilter->getQueries()[0];
        $this->assertInstanceOf(EqualsFilter::class, $equalsNullFilter);
        $this->assertSame($configurationEntity->getFullyQualifiedDalField(), $equalsNullFilter->getField());
        $this->assertNull($equalsNullFilter->getValue());

        $mainFilter = $filter->getFilter();
        $this->assertInstanceOf(RangeFilter::class, $mainFilter);
        $this->assertSame($configurationEntity->getFullyQualifiedDalField(), $mainFilter->getField());
        $this->assertEmpty($mainFilter->getParameters());
    }

    public function testBuildFilterWithNonCompatibleFieldCallsFallback(): void
    {
        $config = new RangeIntervalListingFilterConfigurationEntity();
        $config->setId(Uuid::randomHex());
        $config->setUniqueName(Uuid::randomHex());
        $config->setDalField('some.non.compatible.field');
        $config->setPosition(30);

        $interval = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval->setId(Uuid::randomHex());
        $interval->setMin(0);
        $interval->setMax(100);
        $interval->setPosition(10);

        $config->setIntervals(new RangeIntervalListingFilterConfigurationIntervalCollection([$interval]));

        $requestValue = new RequestValue([]);

        $this->rangeAggregationCompatibilityCheckerMock
            ->expects($this->once())
            ->method('isDalFieldRangeAggregationCompatible')
            ->with($config->getDalField())
            ->willReturn(false);

        $expectedFilterMock = $this->createMock(Filter::class);
        $this->filterBuilderForNonCompatibleFieldsMock
            ->expects($this->once())
            ->method('buildFilter')
            ->with($this->identicalTo($config), $this->identicalTo($requestValue))
            ->willReturnCallback(function (
                RangeIntervalListingFilterConfigurationEntity $passedConfig,
                RequestValue $passedRequestValue
            ) use ($expectedFilterMock, $config, $requestValue): Filter {
                $this->assertSame($config, $passedConfig);
                $this->assertSame($requestValue, $passedRequestValue);

                return $expectedFilterMock;
            });

        $actualFilter = $this->filterBuilder->buildFilter($config, $requestValue);

        $this->assertSame($expectedFilterMock, $actualFilter);
    }
}
