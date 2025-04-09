<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\MultiSelect\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\FilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\RequestValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Bucket\FilterAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Bucket\TermsAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\AndFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;

#[CoversClass(FilterBuilder::class)]
final class FilterBuilderTest extends TestCase
{
    public static function buildFilterWithAllowedElementsWithoutForbiddenElementsProvider(): \Generator
    {
        $filterBuilder = new FilterBuilder();

        $filterConfiguration = new MultiSelectListingFilterConfigurationEntity();
        $filterConfiguration->setDalField('material');
        $filterConfiguration->setAllowedElements(['wood', 'metal', 'plastic']);

        yield 'configuration with allowed elements' => [
            $filterBuilder,
            $filterConfiguration,
            new RequestValue(['wood', 'metal']),
            ['wood', 'metal', 'plastic'],
        ];
    }

    public static function buildFilterWithoutAllowedElementsWithForbiddenElementsProvider(): \Generator
    {
        $filterBuilder = new FilterBuilder();

        $filterConfiguration = new MultiSelectListingFilterConfigurationEntity();
        $filterConfiguration->setDalField('size');
        $filterConfiguration->setForbiddenElements(['XS', 'XXL']);

        yield 'configuration with forbidden elements' => [
            $filterBuilder,
            $filterConfiguration,
            new RequestValue(['S', 'M', 'L']),
            ['XS', 'XXL'],
        ];
    }

    public static function buildFilterWithoutAllowedElementsWithoutForbiddenElementsProvider(): \Generator
    {
        $filterBuilder = new FilterBuilder();

        $filterConfiguration = new MultiSelectListingFilterConfigurationEntity();
        $filterConfiguration->setDalField('color');

        yield [
            $filterBuilder,
            $filterConfiguration,
            new RequestValue(['red', 'blue']),
            $filterConfiguration->getFilterName(),
            true,
            $filterConfiguration->getFullyQualifiedDalField(),
            ['red', 'blue'],
        ];
    }

    /**
     * @param array<string> $expectedAllowedElements
     */
    #[DataProvider('buildFilterWithAllowedElementsWithoutForbiddenElementsProvider')]
    public function testBuildFilterWithAllowedElementsWithoutForbiddenElements(
        FilterBuilder $filterBuilder,
        MultiSelectListingFilterConfigurationEntity $filterConfiguration,
        RequestValue $requestValue,
        array $expectedAllowedElements
    ): void {
        $filter = $filterBuilder->buildFilter($filterConfiguration, $requestValue);

        $aggregations = $filter->getAggregations();
        $filteredAggregation = array_values($aggregations)[0];
        $this->assertInstanceOf(FilterAggregation::class, $filteredAggregation);

        $innerFilters = $filteredAggregation->getFilter();
        $this->assertCount(2, $innerFilters);

        $allowedElementsFilterFound = false;
        foreach ($innerFilters as $innerFilter) {
            if ($innerFilter instanceof EqualsAnyFilter && $innerFilter->getField() === $filterConfiguration->getFullyQualifiedDalField()) {
                $allowedElementsFilterFound = true;
                $this->assertEquals($expectedAllowedElements, $innerFilter->getValue());
                break;
            }
        }

        $this->assertTrue($allowedElementsFilterFound, 'EqualsAnyFilter for allowed elements not found');
    }

    /**
     * @param array<string> $expectedForbiddenElements
     */
    #[DataProvider('buildFilterWithoutAllowedElementsWithForbiddenElementsProvider')]
    public function testBuildFilterWithoutAllowedElementsWithForbiddenElements(
        FilterBuilder $filterBuilder,
        MultiSelectListingFilterConfigurationEntity $filterConfiguration,
        RequestValue $requestValue,
        array $expectedForbiddenElements
    ): void {
        $filter = $filterBuilder->buildFilter($filterConfiguration, $requestValue);

        $aggregations = $filter->getAggregations();
        $filteredAggregation = array_values($aggregations)[0];
        $this->assertInstanceOf(FilterAggregation::class, $filteredAggregation);

        $innerFilters = $filteredAggregation->getFilter();
        $this->assertCount(2, $innerFilters);

        $forbiddenElementsFilterFound = false;
        foreach ($innerFilters as $innerFilter) {
            if ($innerFilter instanceof NotFilter) {
                $innerInnerFilters = $innerFilter->getQueries();
                foreach ($innerInnerFilters as $innerInnerFilter) {
                    if ($innerInnerFilter instanceof EqualsAnyFilter && $innerInnerFilter->getField() === $filterConfiguration->getFullyQualifiedDalField()) {
                        $forbiddenElementsFilterFound = true;
                        $this->assertEquals($expectedForbiddenElements, $innerInnerFilter->getValue());
                        break 2;
                    }
                }
            }
        }

        $this->assertTrue($forbiddenElementsFilterFound, 'NotFilter with EqualsAnyFilter for forbidden elements not found');
    }

    /**
     * @param array<string> $expectedFilterValues
     */
    #[DataProvider('buildFilterWithoutAllowedElementsWithoutForbiddenElementsProvider')]
    public function testBuildFilterWithoutAllowedElementsWithoutForbiddenElements(
        FilterBuilder $filterBuilder,
        MultiSelectListingFilterConfigurationEntity $filterConfiguration,
        RequestValue $requestValue,
        string $expectedName,
        bool $expectedIsFiltered,
        string $expectedFullyQualifiedDalField,
        array $expectedFilterValues,
    ): void {
        $filter = $filterBuilder->buildFilter($filterConfiguration, $requestValue);

        $this->assertSame($expectedName, $filter->getName());
        $this->assertEquals($expectedIsFiltered, $filter->isFiltered());
        $this->assertEquals($expectedFilterValues, $filter->getValues());

        $aggregations = $filter->getAggregations();
        $this->assertCount(1, $aggregations);
        $filteredAggregation = array_values($aggregations)[0];
        $this->assertInstanceOf(FilterAggregation::class, $filteredAggregation);
        $this->assertSame($filterConfiguration->getAggregationName() . '-filter', $filteredAggregation->getName());

        $innerAggregation = $filteredAggregation->getAggregation();
        $this->assertInstanceOf(TermsAggregation::class, $innerAggregation);
        $this->assertSame($filterConfiguration->getAggregationName(), $innerAggregation->getName());
        $this->assertSame($filterConfiguration->getFullyQualifiedDalField(), $innerAggregation->getField());

        $aggregationFilters = $filteredAggregation->getFilter();
        $this->assertCount(1, $aggregationFilters);
        $notNullFilter = array_values($aggregationFilters)[0];
        $this->assertInstanceOf(NotFilter::class, $notNullFilter);
        $this->assertCount(1, $notNullFilter->getQueries());
        $this->assertInstanceOf(EqualsFilter::class, $notNullFilter->getQueries()[0]);
        $this->assertSame($filterConfiguration->getFullyQualifiedDalField(), $notNullFilter->getQueries()[0]->getField());
        $this->assertNull($notNullFilter->getQueries()[0]->getValue());

        $mainFilter = $filter->getFilter();
        $this->assertInstanceOf(AndFilter::class, $mainFilter);
        $andFilters = $mainFilter->getQueries();
        $this->assertCount(2, $andFilters);
        $this->assertInstanceOf(NotFilter::class, $andFilters[0]);
        $this->assertInstanceOf(EqualsAnyFilter::class, $andFilters[1]);
        $this->assertSame($expectedFullyQualifiedDalField, $andFilters[1]->getField());
        $this->assertEquals($expectedFilterValues, $andFilters[1]->getValue());
    }
}
