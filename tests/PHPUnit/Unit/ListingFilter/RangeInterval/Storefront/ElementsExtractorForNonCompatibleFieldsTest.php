<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\RangeInterval\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\ElementBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\ElementsExtractorForNonCompatibleFields;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric\CountResult;
use Shopware\Core\Framework\Uuid\Uuid;

#[CoversClass(ElementsExtractorForNonCompatibleFields::class)]
final class ElementsExtractorForNonCompatibleFieldsTest extends TestCase
{
    private MockObject&ElementBuilderInterface $elementBuilderMock;

    private ElementsExtractorForNonCompatibleFields $elementsExtractor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->elementBuilderMock = $this->createMock(ElementBuilderInterface::class);
        $this->elementsExtractor = new ElementsExtractorForNonCompatibleFields($this->elementBuilderMock);
    }

    public function testExtractElementsFromAggregations(): void
    {
        $configMock = $this->createMock(RangeIntervalListingFilterConfigurationEntity::class);
        $aggregationResultsMock = $this->createMock(AggregationResultCollection::class);

        $intervalId1 = Uuid::randomHex();
        $intervalId2 = Uuid::randomHex();
        $intervalId3 = Uuid::randomHex();

        $aggName1 = 'count_agg_1';
        $aggName2 = 'count_agg_2';
        $aggName3 = 'count_agg_3';

        $intervalEntity1 = $this->createMock(RangeIntervalListingFilterConfigurationIntervalEntity::class);
        $intervalEntity1->method('getId')
            ->willReturn($intervalId1);
        $intervalEntity1->method('getUniqueIdentifier')
            ->willReturn($intervalId1);
        $intervalEntity1->method('getPosition')
            ->willReturn(1);
        $intervalEntity1->method('getCountAggregationName')
            ->willReturn($aggName1);
        $intervalEntity1->method('getIdFromCountAggregationName')
            ->with($aggName1)
            ->willReturn($intervalId1);

        $intervalEntity2 = $this->createMock(RangeIntervalListingFilterConfigurationIntervalEntity::class);
        $intervalEntity2->method('getId')
            ->willReturn($intervalId2);
        $intervalEntity2->method('getUniqueIdentifier')
            ->willReturn($intervalId2);
        $intervalEntity2->method('getPosition')
            ->willReturn(2);
        $intervalEntity2->method('getCountAggregationName')
            ->willReturn($aggName2);
        $intervalEntity2->method('getIdFromCountAggregationName')
            ->with($aggName2)
            ->willReturn($intervalId2);

        $intervalEntity3 = $this->createMock(RangeIntervalListingFilterConfigurationIntervalEntity::class);
        $intervalEntity3->method('getId')
            ->willReturn($intervalId3);
        $intervalEntity3->method('getUniqueIdentifier')
            ->willReturn($intervalId3);
        $intervalEntity3->method('getPosition')
            ->willReturn(3);
        $intervalEntity3->method('getCountAggregationName')
            ->willReturn($aggName3);
        $intervalEntity3->method('getIdFromCountAggregationName')
            ->with($aggName3)
            ->willReturn($intervalId3);

        $intervalCollection = new RangeIntervalListingFilterConfigurationIntervalCollection([
            $intervalEntity3,
            $intervalEntity2,
            $intervalEntity1,
        ]);

        $countResult1 = $this->createMock(CountResult::class);
        $countResult1->method('getCount')
            ->willReturn(5);
        $countResult1->method('getName')
            ->willReturn($aggName1);

        $countResult2 = $this->createMock(CountResult::class);
        $countResult2->method('getCount')
            ->willReturn(10);
        $countResult2->method('getName')
            ->willReturn($aggName2);

        $countResult3 = $this->createMock(CountResult::class);
        $countResult3->method('getCount')
            ->willReturn(0);
        $countResult3->method('getName')
            ->willReturn($aggName3);

        $element1 = new Element($intervalId1, 'Element 1');
        $element2 = new Element($intervalId2, 'Element 2');

        $configMock->method('getIntervals')
            ->willReturn($intervalCollection);

        $aggregationResultsMock
            ->expects($this->exactly(3))
            ->method('get')
            ->willReturnMap([[$aggName1, $countResult1], [$aggName2, $countResult2], [$aggName3, $countResult3]]);

        $this->elementBuilderMock
            ->expects($this->exactly(2))
            ->method('buildElement')
            ->willReturnMap([[$intervalEntity1, $element1], [$intervalEntity2, $element2]]);

        $result = $this->elementsExtractor->extractElementsFromAggregations($configMock, $aggregationResultsMock);

        $this->assertCount(2, $result);
        $this->assertSame([$element1, $element2], $result);
    }

    public function testExtractElementsFromAggregationsWithNoIntervals(): void
    {
        $configMock = $this->createMock(RangeIntervalListingFilterConfigurationEntity::class);
        $aggregationResultsMock = $this->createMock(AggregationResultCollection::class);

        $configMock->method('getIntervals')
            ->willReturn(new RangeIntervalListingFilterConfigurationIntervalCollection());

        $aggregationResultsMock->expects($this->never())
            ->method('get');
        $this->elementBuilderMock->expects($this->never())
            ->method('buildElement');

        $result = $this->elementsExtractor->extractElementsFromAggregations($configMock, $aggregationResultsMock);

        $this->assertEmpty($result);
    }

    public function testExtractElementsThrowsExceptionIfAggregationNotFound(): void
    {
        $configMock = $this->createMock(RangeIntervalListingFilterConfigurationEntity::class);
        $aggregationResultsMock = $this->createMock(AggregationResultCollection::class);

        $intervalId1 = Uuid::randomHex();
        $aggName1 = 'missing_count_agg';

        $intervalEntity1 = $this->createMock(RangeIntervalListingFilterConfigurationIntervalEntity::class);
        $intervalEntity1->method('getId')
            ->willReturn($intervalId1);
        $intervalEntity1->method('getUniqueIdentifier')
            ->willReturn($intervalId1);
        $intervalEntity1->method('getPosition')
            ->willReturn(1);
        $intervalEntity1->method('getCountAggregationName')
            ->willReturn($aggName1);

        $intervalCollection = new RangeIntervalListingFilterConfigurationIntervalCollection([$intervalEntity1]);
        $configMock->method('getIntervals')
            ->willReturn($intervalCollection);

        $aggregationResultsMock
            ->expects($this->once())
            ->method('get')
            ->with($aggName1)
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('The aggregation "%s" could not be found.', $aggName1));

        $this->elementsExtractor->extractElementsFromAggregations($configMock, $aggregationResultsMock);
    }

    public function testExtractElementsThrowsExceptionIfIntervalsNotLoaded(): void
    {
        $configMock = $this->createMock(RangeIntervalListingFilterConfigurationEntity::class);
        $aggregationResultsMock = $this->createMock(AggregationResultCollection::class);

        $configMock->method('getIntervals')
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('`intervals` not loaded in `RangeIntervalListingFilterConfigurationEntity`');

        $this->elementsExtractor->extractElementsFromAggregations($configMock, $aggregationResultsMock);
    }

    public function testExtractElementsThrowsExceptionIfNotCountResult(): void
    {
        $configMock = $this->createMock(RangeIntervalListingFilterConfigurationEntity::class);
        $aggregationResultsMock = $this->createMock(AggregationResultCollection::class);
        $wrongAggregationResultMock = $this->createMock(AggregationResult::class);

        $intervalId1 = Uuid::randomHex();
        $aggName1 = 'wrong_type_agg';

        $intervalEntity1 = $this->createMock(RangeIntervalListingFilterConfigurationIntervalEntity::class);
        $intervalEntity1->method('getId')
            ->willReturn($intervalId1);
        $intervalEntity1->method('getUniqueIdentifier')
            ->willReturn($intervalId1);
        $intervalEntity1->method('getPosition')
            ->willReturn(1);
        $intervalEntity1->method('getCountAggregationName')
            ->willReturn($aggName1);

        $intervalCollection = new RangeIntervalListingFilterConfigurationIntervalCollection([$intervalEntity1]);
        $configMock->method('getIntervals')
            ->willReturn($intervalCollection);

        $aggregationResultsMock
            ->expects($this->once())
            ->method('get')
            ->with($aggName1)
            ->willReturn($wrongAggregationResultMock);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('The aggregation result "%s" is not a count result.', $aggName1));

        $this->elementsExtractor->extractElementsFromAggregations($configMock, $aggregationResultsMock);
    }
}
