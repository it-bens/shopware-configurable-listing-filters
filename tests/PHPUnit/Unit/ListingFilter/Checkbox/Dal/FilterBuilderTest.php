<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\Checkbox\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\FilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\RequestValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Metric\MaxAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

#[CoversClass(FilterBuilder::class)]
final class FilterBuilderTest extends TestCase
{
    public static function buildFilterProvider(): \Generator
    {
        $filterBuilder = new FilterBuilder();
        $filterConfiguration = new CheckboxListingFilterConfigurationEntity();
        $filterConfiguration->setDalField('isCloseout');

        yield [
            $filterBuilder,
            $filterConfiguration,
            new RequestValue(true),
            $filterConfiguration->getFilterName(),
            true,
            $filterConfiguration->getAggregationName(),
            $filterConfiguration->getFullyQualifiedDalField(),
            true,
        ];
    }

    #[DataProvider('buildFilterProvider')]
    public function testBuildFilter(
        FilterBuilder $filterBuilder,
        CheckboxListingFilterConfigurationEntity $filterConfiguration,
        RequestValue $requestValue,
        string $expectedName,
        bool $expectedIsFiltered,
        string $expectedAggregationName,
        string $expectedFullyQualifiedDalField,
        bool|float|int|null|string $expectedFilterValue,
    ): void {
        $filter = $filterBuilder->buildFilter($filterConfiguration, $requestValue);
        $this->assertSame($expectedName, $filter->getName());
        $this->assertEquals($expectedIsFiltered, $filter->isFiltered());

        $aggregations = $filter->getAggregations();
        $this->assertCount(1, $aggregations);
        $aggregation = array_values($aggregations)[0];
        $this->assertInstanceOf(MaxAggregation::class, $aggregation);
        $this->assertSame($expectedAggregationName, $aggregation->getName());
        $this->assertSame($expectedFullyQualifiedDalField, $aggregation->getField());

        $fieldFilter = $filter->getFilter();
        $this->assertInstanceOf(EqualsFilter::class, $fieldFilter);
        $this->assertSame($expectedFullyQualifiedDalField, $fieldFilter->getField());
        $this->assertEquals($expectedFilterValue, $fieldFilter->getValue());
    }
}
