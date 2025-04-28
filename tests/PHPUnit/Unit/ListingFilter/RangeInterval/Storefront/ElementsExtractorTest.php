<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\RangeInterval\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\RangeAggregationCompatibilityCheckerInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\ElementBuilderInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\ElementsExtractor;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\ElementsExtractorInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric\RangeResult;
use Shopware\Core\Framework\Uuid\Uuid;

#[CoversClass(ElementsExtractor::class)]
final class ElementsExtractorTest extends TestCase
{
    private MockObject&ElementBuilderInterface $elementBuilderMock;

    private ElementsExtractor $elementsExtractor;

    private MockObject&ElementsExtractorInterface $elementsExtractorForNonCompatibleFieldsMock;

    private MockObject&RangeAggregationCompatibilityCheckerInterface $rangeAggregationCompatibilityCheckerMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rangeAggregationCompatibilityCheckerMock = $this->createMock(RangeAggregationCompatibilityCheckerInterface::class);
        $this->elementsExtractorForNonCompatibleFieldsMock = $this->createMock(ElementsExtractorInterface::class);
        $this->elementBuilderMock = $this->createMock(ElementBuilderInterface::class);

        $this->elementsExtractor = new ElementsExtractor(
            $this->rangeAggregationCompatibilityCheckerMock,
            $this->elementsExtractorForNonCompatibleFieldsMock,
            $this->elementBuilderMock
        );
    }

    public function testExtractElementsFromAggregationsForCompatibleField(): void
    {
        $configMock = $this->createMock(RangeIntervalListingFilterConfigurationEntity::class);
        $aggregationResultsMock = $this->createMock(AggregationResultCollection::class);
        $rangeResultMock = $this->createMock(RangeResult::class);
        $intervalCollectionMock = $this->createMock(RangeIntervalListingFilterConfigurationIntervalCollection::class);

        $dalField = 'product.price';
        $aggregationName = 'price_range_agg';
        $intervalId1 = Uuid::randomHex();
        $intervalId2 = Uuid::randomHex();
        $intervalIdNotFound = Uuid::randomHex();

        $intervalEntity1 = $this->createMock(RangeIntervalListingFilterConfigurationIntervalEntity::class);
        $intervalEntity1->method('getId')
            ->willReturn($intervalId1);
        $intervalEntity1->method('getUniqueIdentifier')
            ->willReturn($intervalId1);

        $intervalEntity2 = $this->createMock(RangeIntervalListingFilterConfigurationIntervalEntity::class);
        $intervalEntity2->method('getId')
            ->willReturn($intervalId2);
        $intervalEntity2->method('getUniqueIdentifier')
            ->willReturn($intervalId2);

        $element1 = new Element($intervalId1, 'Element 1');
        $element2 = new Element($intervalId2, 'Element 2');

        $configMock->method('getDalField')
            ->willReturn($dalField);
        $configMock->method('getAggregationName')
            ->willReturn($aggregationName);
        $configMock->method('getIntervals')
            ->willReturn($intervalCollectionMock);

        $this->rangeAggregationCompatibilityCheckerMock
            ->expects($this->once())
            ->method('isDalFieldRangeAggregationCompatible')
            ->with($dalField)
            ->willReturn(true);

        $aggregationResultsMock
            ->expects($this->once())
            ->method('get')
            ->with($aggregationName)
            ->willReturn($rangeResultMock);

        $rangeResultMock
            ->expects($this->once())
            ->method('getRanges')
            ->willReturn([
                $intervalId1 => [
                    'count' => 5,
                ],
                $intervalId2 => [
                    'count' => 10,
                ],
                $intervalIdNotFound => [
                    'count' => 3,
                ],
            ]);

        $intervalCollectionMock
            ->expects($this->exactly(3))
            ->method('get')
            ->willReturnMap([[$intervalId1, $intervalEntity1], [$intervalId2, $intervalEntity2], [$intervalIdNotFound, null]]);

        $this->elementBuilderMock
            ->expects($this->exactly(2))
            ->method('buildElement')
            ->willReturnMap([[$intervalEntity1, $element1], [$intervalEntity2, $element2]]);

        $this->elementsExtractorForNonCompatibleFieldsMock
            ->expects($this->never())
            ->method('extractElementsFromAggregations');

        $result = $this->elementsExtractor->extractElementsFromAggregations($configMock, $aggregationResultsMock);

        $this->assertCount(2, $result);
        $this->assertContainsEquals($element1, $result);
        $this->assertContainsEquals($element2, $result);
        $this->assertEqualsCanonicalizing([$element1, $element2], $result);
    }

    public function testExtractElementsFromAggregationsForNonCompatibleField(): void
    {
        $configMock = $this->createMock(RangeIntervalListingFilterConfigurationEntity::class);
        $aggregationResultsMock = $this->createMock(AggregationResultCollection::class);

        $dalField = 'product.customField';
        $expectedElements = [new Element(Uuid::randomHex(), 'Element A'), new Element(Uuid::randomHex(), 'Element B')];

        $configMock->method('getDalField')
            ->willReturn($dalField);

        $this->rangeAggregationCompatibilityCheckerMock
            ->expects($this->once())
            ->method('isDalFieldRangeAggregationCompatible')
            ->with($dalField)
            ->willReturn(false);

        $this->elementsExtractorForNonCompatibleFieldsMock
            ->expects($this->once())
            ->method('extractElementsFromAggregations')
            ->with($this->identicalTo($configMock), $this->identicalTo($aggregationResultsMock))
            ->willReturn($expectedElements);

        $this->elementBuilderMock
            ->expects($this->never())
            ->method('buildElement');

        $result = $this->elementsExtractor->extractElementsFromAggregations($configMock, $aggregationResultsMock);

        $this->assertSame($expectedElements, $result);
    }

    public function testExtractElementsThrowsExceptionIfAggregationNotFound(): void
    {
        $configMock = $this->createMock(RangeIntervalListingFilterConfigurationEntity::class);
        $aggregationResultsMock = $this->createMock(AggregationResultCollection::class);

        $dalField = 'product.price';
        $aggregationName = 'missing_agg';

        $configMock->method('getDalField')
            ->willReturn($dalField);
        $configMock->method('getAggregationName')
            ->willReturn($aggregationName);

        $this->rangeAggregationCompatibilityCheckerMock
            ->expects($this->once())
            ->method('isDalFieldRangeAggregationCompatible')
            ->with($dalField)
            ->willReturn(true);

        $aggregationResultsMock
            ->expects($this->once())
            ->method('get')
            ->with($aggregationName)
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('The aggregation "%s" could not be found.', $aggregationName));

        $this->elementsExtractor->extractElementsFromAggregations($configMock, $aggregationResultsMock);
    }

    public function testExtractElementsThrowsExceptionIfNotRangeResult(): void
    {
        $configMock = $this->createMock(RangeIntervalListingFilterConfigurationEntity::class);
        $aggregationResultsMock = $this->createMock(AggregationResultCollection::class);
        $wrongAggregationResultMock = $this->createMock(AggregationResult::class);

        $dalField = 'product.price';
        $aggregationName = 'wrong_type_agg';

        $configMock->method('getDalField')
            ->willReturn($dalField);
        $configMock->method('getAggregationName')
            ->willReturn($aggregationName);

        $this->rangeAggregationCompatibilityCheckerMock
            ->expects($this->once())
            ->method('isDalFieldRangeAggregationCompatible')
            ->with($dalField)
            ->willReturn(true);

        $aggregationResultsMock
            ->expects($this->once())
            ->method('get')
            ->with($aggregationName)
            ->willReturn($wrongAggregationResultMock);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('The aggregation result "%s" is not a range result.', $aggregationName));

        $this->elementsExtractor->extractElementsFromAggregations($configMock, $aggregationResultsMock);
    }
}
