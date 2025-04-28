<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationIntervalEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RangeIntervalListingFilterConfigurationIntervalCollection::class)]
final class RangeIntervalListingFilterConfigurationIntervalCollectionTest extends TestCase
{
    public function testGetApiAlias(): void
    {
        $collection = new RangeIntervalListingFilterConfigurationIntervalCollection();

        $this->assertSame('itb_lfc_collection_range_interval_interval', $collection->getApiAlias());
    }

    public function testGetElementsSortedByPosition(): void
    {
        $collection = new RangeIntervalListingFilterConfigurationIntervalCollection();

        $interval1 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval1->setId('interval-1');
        $interval1->setPosition(3);

        $interval2 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval2->setId('interval-2');
        $interval2->setPosition(1);

        $interval3 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval3->setId('interval-3');
        $interval3->setPosition(2);

        $collection->add($interval1);
        $collection->add($interval2);
        $collection->add($interval3);

        $sortedElements = $collection->getElementsSortedByPosition();

        $this->assertCount(3, $sortedElements);
        $this->assertSame($interval2, $sortedElements[0]); // Position 1
        $this->assertSame($interval3, $sortedElements[1]); // Position 2
        $this->assertSame($interval1, $sortedElements[2]); // Position 3
    }

    public function testGetElementsSortedWithSamePosition(): void
    {
        $collection = new RangeIntervalListingFilterConfigurationIntervalCollection();

        $interval1 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval1->setId('interval-1');
        $interval1->setPosition(1);

        $interval2 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval2->setId('interval-2');
        $interval2->setPosition(1); // Same position

        $collection->add($interval1);
        $collection->add($interval2);

        $sortedElements = $collection->getElementsSortedByPosition();

        $this->assertCount(2, $sortedElements);
        // The order is not guaranteed to be specific for same positions
    }

    public function testGetExpectedClass(): void
    {
        $collection = new RangeIntervalListingFilterConfigurationIntervalCollection();

        $reflectionClass = new \ReflectionClass(RangeIntervalListingFilterConfigurationIntervalCollection::class);
        $method = $reflectionClass->getMethod('getExpectedClass');

        $this->assertSame(RangeIntervalListingFilterConfigurationIntervalEntity::class, $method->invoke($collection));
    }

    public function testGetIterator(): void
    {
        $collection = new RangeIntervalListingFilterConfigurationIntervalCollection();

        $interval1 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval1->setId('interval-1');
        $interval1->setPosition(3);

        $interval2 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval2->setId('interval-2');
        $interval2->setPosition(1);

        $interval3 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval3->setId('interval-3');
        $interval3->setPosition(2);

        $collection->add($interval1);
        $collection->add($interval2);
        $collection->add($interval3);

        $iterations = 0;
        $ids = [];

        foreach ($collection as $item) {
            $iterations++;
            $ids[] = $item->getId();
        }

        $this->assertSame(3, $iterations);
        $this->assertSame(['interval-2', 'interval-3', 'interval-1'], $ids);
    }
}
