<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\Product\SalesChannel\Listing\Service;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\Service\FilterCollectionEnricher;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\FilterBuilderInterface as CheckboxFilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\RequestValue as CheckboxRequestValue;
use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Dal\RequestValueBuilderInterface as CheckboxRequestValueBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\FilterBuilderInterface as MultiSelectFilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\RequestValue as MultiSelectRequestValue;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Dal\RequestValueBuilderInterface as MultiSelectRequestValueBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\FilterBuilderInterface as RangeFilterBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\RequestValue as RangeRequestValue;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\RequestValueBuilderInterface as RangeRequestValueBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;
use Shopware\Core\Content\Product\SalesChannel\Listing\FilterCollection;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(FilterCollectionEnricher::class)]
final class FilterCollectionEnricherTest extends TestCase
{
    public static function enrichFilterCollectionProvider(): \Generator
    {
        $checkboxListingFilterConfigurationEntity = new CheckboxListingFilterConfigurationEntity();
        $checkboxListingFilterConfigurationEntity->setUniqueIdentifier('checkbox');
        $checkboxListingFilterConfigurationEntity->setDalField('checkboxField');
        $checkboxListingFilterConfigurationEntity->setPosition(1);
        $checkboxListingFilterConfigurationEntity->setEnabled(true);

        $checkboxFilter = self::createStub(Filter::class);

        $multiSelectListingFilterConfigurationEntity = new MultiSelectListingFilterConfigurationEntity();
        $multiSelectListingFilterConfigurationEntity->setUniqueIdentifier('multiSelect');
        $multiSelectListingFilterConfigurationEntity->setDalField('multiSelectField');
        $multiSelectListingFilterConfigurationEntity->setPosition(2);
        $multiSelectListingFilterConfigurationEntity->setEnabled(true);

        $multiSelectFilter = self::createStub(Filter::class);

        $rangeListingFilterConfigurationEntity = new RangeListingFilterConfigurationEntity();
        $rangeListingFilterConfigurationEntity->setUniqueIdentifier('range');
        $rangeListingFilterConfigurationEntity->setDalField('rangeField');
        $rangeListingFilterConfigurationEntity->setPosition(3);
        $rangeListingFilterConfigurationEntity->setEnabled(true);

        $rangeFilter = self::createStub(Filter::class);

        yield [
            $checkboxListingFilterConfigurationEntity,
            $checkboxFilter,
            $multiSelectListingFilterConfigurationEntity,
            $multiSelectFilter,
            $rangeListingFilterConfigurationEntity,
            $rangeFilter,
        ];
    }

    public static function enrichFilterCollectionWithDisabledFilterConfigurationsProvider(): \Generator
    {
        $checkboxListingFilterConfigurationEntity = new CheckboxListingFilterConfigurationEntity();
        $checkboxListingFilterConfigurationEntity->setUniqueIdentifier('checkbox');
        $checkboxListingFilterConfigurationEntity->setDalField('checkboxField');
        $checkboxListingFilterConfigurationEntity->setPosition(1);
        $checkboxListingFilterConfigurationEntity->setEnabled(false);

        $multiSelectListingFilterConfigurationEntity = new MultiSelectListingFilterConfigurationEntity();
        $multiSelectListingFilterConfigurationEntity->setUniqueIdentifier('multiSelect');
        $multiSelectListingFilterConfigurationEntity->setDalField('multiSelectField');
        $multiSelectListingFilterConfigurationEntity->setPosition(2);
        $multiSelectListingFilterConfigurationEntity->setEnabled(false);

        $rangeListingFilterConfigurationEntity = new RangeListingFilterConfigurationEntity();
        $rangeListingFilterConfigurationEntity->setUniqueIdentifier('range');
        $rangeListingFilterConfigurationEntity->setDalField('rangeField');
        $rangeListingFilterConfigurationEntity->setPosition(3);
        $rangeListingFilterConfigurationEntity->setEnabled(false);

        yield [
            $checkboxListingFilterConfigurationEntity,
            $multiSelectListingFilterConfigurationEntity,
            $rangeListingFilterConfigurationEntity,
        ];
    }

    #[DataProvider('enrichFilterCollectionProvider')]
    public function testEnrichFilterCollection(
        CheckboxListingFilterConfigurationEntity $checkboxListingFilterConfigurationEntity,
        Filter $checkboxFilter,
        MultiSelectListingFilterConfigurationEntity $multiSelectListingFilterConfigurationEntity,
        Filter $multiSelectFilter,
        RangeListingFilterConfigurationEntity $rangeListingFilterConfigurationEntity,
        Filter $rangeFilter,
    ): void {
        $request = $this->createStub(Request::class);

        $filterCollectionAddCall = 0;
        $expectedFilterCollectionAddCallArgs = [$checkboxFilter, $multiSelectFilter, $rangeFilter];
        $filterCollection = $this->createMock(FilterCollection::class);
        $filterCollection->expects($this->exactly(3))
            ->method('add')
            ->willReturnCallback(function (Filter $filter) use (&$filterCollectionAddCall, $expectedFilterCollectionAddCallArgs): void {
                $this->assertSame($expectedFilterCollectionAddCallArgs[$filterCollectionAddCall], $filter);
                $filterCollectionAddCall++;
            });

        $checkboxRequestValue = new CheckboxRequestValue(true);
        $checkboxRequestValueBuilder = $this->createMock(CheckboxRequestValueBuilder::class);
        $checkboxRequestValueBuilder->method('buildRequestValue')
            ->willReturnCallback(function (CheckboxListingFilterConfigurationEntity $configurationEntityArgument, Request $requestArgument) use (
                $checkboxListingFilterConfigurationEntity,
                $request,
                $checkboxRequestValue
            ): CheckboxRequestValue {
                $this->assertSame($checkboxListingFilterConfigurationEntity, $configurationEntityArgument);
                $this->assertSame($request, $requestArgument);

                return $checkboxRequestValue;
            });

        $checkboxFilterBuilder = $this->createMock(CheckboxFilterBuilder::class);
        $checkboxFilterBuilder->method('buildFilter')
            ->willReturnCallback(
                function (
                    CheckboxListingFilterConfigurationEntity $filterConfigurationEntityArgument,
                    CheckboxRequestValue $requestValueArgument
                ) use ($checkboxListingFilterConfigurationEntity, $checkboxRequestValue, $checkboxFilter): Filter {
                    $this->assertSame($checkboxListingFilterConfigurationEntity, $filterConfigurationEntityArgument);
                    $this->assertSame($checkboxRequestValue, $requestValueArgument);

                    return $checkboxFilter;
                }
            );

        $multiSelectRequestValue = new MultiSelectRequestValue(['element1', 'element2']);
        $multiSelectRequestValueBuilder = $this->createMock(MultiSelectRequestValueBuilder::class);
        $multiSelectRequestValueBuilder->method('buildRequestValue')
            ->willReturnCallback(
                function (MultiSelectListingFilterConfigurationEntity $configurationEntityArgument, Request $requestArgument) use (
                    $multiSelectListingFilterConfigurationEntity,
                    $request,
                    $multiSelectRequestValue
                ): MultiSelectRequestValue {
                    $this->assertSame($multiSelectListingFilterConfigurationEntity, $configurationEntityArgument);
                    $this->assertSame($request, $requestArgument);

                    return $multiSelectRequestValue;
                }
            );

        $multiSelectFilterBuilder = $this->createMock(MultiSelectFilterBuilder::class);
        $multiSelectFilterBuilder->method('buildFilter')
            ->willReturnCallback(
                function (
                    MultiSelectListingFilterConfigurationEntity $filterConfigurationEntityArgument,
                    MultiSelectRequestValue $requestValueArgument
                ) use ($multiSelectListingFilterConfigurationEntity, $multiSelectRequestValue, $multiSelectFilter): Filter {
                    $this->assertSame($multiSelectListingFilterConfigurationEntity, $filterConfigurationEntityArgument);
                    $this->assertSame($multiSelectRequestValue, $requestValueArgument);

                    return $multiSelectFilter;
                }
            );

        $rangeRequestValue = new RangeRequestValue(1, 10);
        $rangeRequestValueBuilder = $this->createMock(RangeRequestValueBuilder::class);
        $rangeRequestValueBuilder->method('buildRequestValue')
            ->willReturnCallback(function (RangeListingFilterConfigurationEntity $configurationEntityArgument, Request $requestArgument) use (
                $rangeListingFilterConfigurationEntity,
                $request,
                $rangeRequestValue
            ): RangeRequestValue {
                $this->assertSame($rangeListingFilterConfigurationEntity, $configurationEntityArgument);
                $this->assertSame($request, $requestArgument);

                return $rangeRequestValue;
            });

        $rangeFilterBuilder = $this->createMock(RangeFilterBuilder::class);
        $rangeFilterBuilder->method('buildFilter')
            ->willReturnCallback(
                function (
                    RangeListingFilterConfigurationEntity $filterConfigurationEntityArgument,
                    RangeRequestValue $requestValueArgument
                ) use ($rangeListingFilterConfigurationEntity, $rangeRequestValue, $rangeFilter): Filter {
                    $this->assertSame($rangeListingFilterConfigurationEntity, $filterConfigurationEntityArgument);
                    $this->assertSame($rangeRequestValue, $requestValueArgument);

                    return $rangeFilter;
                }
            );

        $filterCollectionEnricher = new FilterCollectionEnricher(
            $checkboxRequestValueBuilder,
            $checkboxFilterBuilder,
            $multiSelectRequestValueBuilder,
            $multiSelectFilterBuilder,
            $rangeRequestValueBuilder,
            $rangeFilterBuilder,
        );

        $listingFilterConfigurationCollection = new ListingFilterConfigurationCollection(
            new CheckboxListingFilterConfigurationCollection([$checkboxListingFilterConfigurationEntity]),
            new MultiSelectListingFilterConfigurationCollection([$multiSelectListingFilterConfigurationEntity]),
            new RangeListingFilterConfigurationCollection([$rangeListingFilterConfigurationEntity])
        );

        $filterCollectionEnricher->enrichFilterCollection($listingFilterConfigurationCollection, $request, $filterCollection);
    }

    #[DataProvider('enrichFilterCollectionWithDisabledFilterConfigurationsProvider')]
    public function testEnrichFilterCollectionWithDisabledFilterConfigurations(
        CheckboxListingFilterConfigurationEntity $checkboxListingFilterConfigurationEntity,
        MultiSelectListingFilterConfigurationEntity $multiSelectListingFilterConfigurationEntity,
        RangeListingFilterConfigurationEntity $rangeListingFilterConfigurationEntity,
    ): void {
        $checkboxRequestValueBuilder = $this->createMock(CheckboxRequestValueBuilder::class);
        $checkboxRequestValueBuilder->expects($this->never())
            ->method('buildRequestValue');
        $checkboxFilterBuilder = $this->createMock(CheckboxFilterBuilder::class);
        $checkboxFilterBuilder->expects($this->never())
            ->method('buildFilter');

        $multiSelectRequestValueBuilder = $this->createMock(MultiSelectRequestValueBuilder::class);
        $multiSelectRequestValueBuilder->expects($this->never())
            ->method('buildRequestValue');
        $multiSelectFilterBuilder = $this->createMock(MultiSelectFilterBuilder::class);
        $multiSelectFilterBuilder->expects($this->never())
            ->method('buildFilter');

        $rangeRequestValueBuilder = $this->createMock(RangeRequestValueBuilder::class);
        $rangeRequestValueBuilder->expects($this->never())
            ->method('buildRequestValue');
        $rangeFilterBuilder = $this->createMock(RangeFilterBuilder::class);
        $rangeFilterBuilder->expects($this->never())
            ->method('buildFilter');

        $filterCollection = $this->createMock(FilterCollection::class);
        $filterCollection->expects($this->never())
            ->method('add');

        $filterCollectionEnricher = new FilterCollectionEnricher(
            $checkboxRequestValueBuilder,
            $checkboxFilterBuilder,
            $multiSelectRequestValueBuilder,
            $multiSelectFilterBuilder,
            $rangeRequestValueBuilder,
            $rangeFilterBuilder,
        );

        $listingFilterConfigurationCollection = new ListingFilterConfigurationCollection(
            new CheckboxListingFilterConfigurationCollection([$checkboxListingFilterConfigurationEntity]),
            new MultiSelectListingFilterConfigurationCollection([$multiSelectListingFilterConfigurationEntity]),
            new RangeListingFilterConfigurationCollection([$rangeListingFilterConfigurationEntity])
        );

        $filterCollectionEnricher->enrichFilterCollection(
            $listingFilterConfigurationCollection,
            $this->createStub(Request::class),
            $filterCollection
        );
    }
}
