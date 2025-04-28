<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\RangeInterval\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\FilterBuilderForNonCompatibleFields;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\RequestValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Bucket\FilterAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Metric\CountAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;
use Shopware\Core\Framework\Uuid\Uuid;

#[CoversClass(FilterBuilderForNonCompatibleFields::class)]
final class FilterBuilderForNonCompatibleFieldsTest extends TestCase
{
    public function testBuildFilterThrowsExceptionWhenIntervalsNotLoaded(): void
    {
        $config = new RangeIntervalListingFilterConfigurationEntity();
        $config->setId(Uuid::randomHex());
        $config->setUniqueName('test_filter_no_intervals');
        $config->setDalField('product.width');
        $config->setPosition(40);

        $requestValue = new RequestValue([]);

        $filterBuilder = new FilterBuilderForNonCompatibleFields();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('`intervals` not loaded in `RangeIntervalListingFilterConfigurationEntity`');

        $filterBuilder->buildFilter($config, $requestValue);
    }

    public function testBuildFilterWithEmptyIntervalCollectionInConfig(): void
    {
        $config = new RangeIntervalListingFilterConfigurationEntity();
        $config->setId(Uuid::randomHex());
        $config->setUniqueName('test_filter_empty_config');
        $config->setDalField('product.height');
        $config->setPosition(30);

        $intervalCollection = new RangeIntervalListingFilterConfigurationIntervalCollection([]);
        $config->setIntervals($intervalCollection);

        $requestValue = new RequestValue([]);

        $filterBuilder = new FilterBuilderForNonCompatibleFields();
        $filter = $filterBuilder->buildFilter($config, $requestValue);

        $this->assertInstanceOf(Filter::class, $filter);
        $this->assertSame($config->getFilterName(), $filter->getName());
        $this->assertFalse($filter->isFiltered());
        $this->assertEmpty($filter->getValues());

        $mainFilter = $filter->getFilter();
        $this->assertInstanceOf(RangeFilter::class, $mainFilter);
        $this->assertSame($config->getFullyQualifiedDalField(), $mainFilter->getField());
        $this->assertEmpty($mainFilter->getParameters());

        $aggregations = $filter->getAggregations();
        $this->assertCount(0, $aggregations);
    }

    public function testBuildFilterWithIntervalsAndRequestValue(): void
    {
        $config = new RangeIntervalListingFilterConfigurationEntity();
        $configId = Uuid::randomHex();
        $config->setId($configId);
        $config->setUniqueName('test_filter');
        $config->setDalField('product.price');
        $config->setPosition(10);

        $interval1 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval1->setId(Uuid::randomHex());
        $interval1->setMin(0);
        $interval1->setMax(100);
        $interval1->setPosition(1);
        $interval1->setRangeIntervalListingFilterConfigurationId($configId);
        $interval1->setRangeIntervalListingFilterConfiguration($config);

        $interval2 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval2->setId(Uuid::randomHex());
        $interval2->setMin(100);
        $interval2->setMax(200);
        $interval2->setPosition(2);
        $interval2->setRangeIntervalListingFilterConfigurationId($configId);
        $interval2->setRangeIntervalListingFilterConfiguration($config);

        $intervalCollection = new RangeIntervalListingFilterConfigurationIntervalCollection([$interval1, $interval2]);
        $config->setIntervals($intervalCollection);

        $requestValue = new RequestValue([$interval1]);
        $expectedRequestRange = [
            RangeFilter::GTE => 0,
            RangeFilter::LTE => 100,
        ];

        $filterBuilder = new FilterBuilderForNonCompatibleFields();
        $filter = $filterBuilder->buildFilter($config, $requestValue);

        $this->assertInstanceOf(Filter::class, $filter);
        $this->assertSame($config->getFilterName(), $filter->getName());
        $this->assertTrue($filter->isFiltered());
        $this->assertSame($expectedRequestRange, $filter->getValues());

        $mainFilter = $filter->getFilter();
        $this->assertInstanceOf(RangeFilter::class, $mainFilter);
        $this->assertSame($config->getFullyQualifiedDalField(), $mainFilter->getField());
        $this->assertSame($expectedRequestRange, $mainFilter->getParameters());

        $aggregations = $filter->getAggregations();
        $this->assertCount(2, $aggregations);

        $agg1 = $aggregations[0];
        $this->assertInstanceOf(FilterAggregation::class, $agg1);
        $this->assertSame($interval1->getCountAggregationName(), $agg1->getName());

        $innerAgg1 = $agg1->getAggregation();
        $this->assertInstanceOf(CountAggregation::class, $innerAgg1);
        $this->assertSame($interval1->getCountAggregationName(), $innerAgg1->getName());
        $this->assertSame($config->getFullyQualifiedDalField(), $innerAgg1->getField());

        $aggFilter1 = $agg1->getFilter();
        $this->assertCount(1, $aggFilter1);
        $this->assertInstanceOf(RangeFilter::class, $aggFilter1[0]);
        $this->assertSame($config->getFullyQualifiedDalField(), $aggFilter1[0]->getField());
        $this->assertSame($interval1->getRangeForFilter(), $aggFilter1[0]->getParameters());

        $agg2 = $aggregations[1];
        $this->assertInstanceOf(FilterAggregation::class, $agg2);
        $this->assertSame($interval2->getCountAggregationName(), $agg2->getName());

        $innerAgg2 = $agg2->getAggregation();
        $this->assertInstanceOf(CountAggregation::class, $innerAgg2);
        $this->assertSame($interval2->getCountAggregationName(), $innerAgg2->getName());
        $this->assertSame($config->getFullyQualifiedDalField(), $innerAgg2->getField());

        $aggFilter2 = $agg2->getFilter();
        $this->assertCount(1, $aggFilter2);
        $this->assertInstanceOf(RangeFilter::class, $aggFilter2[0]);
        $this->assertSame($config->getFullyQualifiedDalField(), $aggFilter2[0]->getField());
        $this->assertSame($interval2->getRangeForFilter(), $aggFilter2[0]->getParameters());
    }

    public function testBuildFilterWithNoSelectedIntervals(): void
    {
        $config = new RangeIntervalListingFilterConfigurationEntity();
        $configId = Uuid::randomHex();
        $config->setId($configId);
        $config->setUniqueName('test_filter_no_selection');
        $config->setDalField('product.stock');
        $config->setPosition(20);

        $interval1 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval1Id = Uuid::randomHex();
        $interval1->setId($interval1Id);
        $interval1->setMin(0);
        $interval1->setMax(10);
        $interval1->setPosition(1);
        $interval1->setRangeIntervalListingFilterConfigurationId($configId);
        $interval1->setRangeIntervalListingFilterConfiguration($config);

        $intervalCollection = new RangeIntervalListingFilterConfigurationIntervalCollection([$interval1]);
        $config->setIntervals($intervalCollection);

        $requestValue = new RequestValue([]);
        $expectedRequestRange = [];

        $filterBuilder = new FilterBuilderForNonCompatibleFields();
        $filter = $filterBuilder->buildFilter($config, $requestValue);

        $this->assertInstanceOf(Filter::class, $filter);
        $this->assertSame($config->getFilterName(), $filter->getName());
        $this->assertFalse($filter->isFiltered());
        $this->assertSame($expectedRequestRange, $filter->getValues());

        $mainFilter = $filter->getFilter();
        $this->assertInstanceOf(RangeFilter::class, $mainFilter);
        $this->assertSame($config->getFullyQualifiedDalField(), $mainFilter->getField());
        $this->assertSame($expectedRequestRange, $mainFilter->getParameters());

        $aggregations = $filter->getAggregations();
        $this->assertCount(1, $aggregations);

        $agg1 = $aggregations[0];
        $this->assertInstanceOf(FilterAggregation::class, $agg1);
        $this->assertSame($interval1->getCountAggregationName(), $agg1->getName());
    }
}
