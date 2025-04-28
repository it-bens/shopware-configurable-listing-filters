<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\RangeInterval;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RangeIntervalListingFilterConfigurationCollection::class)]
final class RangeIntervalListingFilterConfigurationCollectionTest extends TestCase
{
    public function testGetApiAlias(): void
    {
        $collection = new RangeIntervalListingFilterConfigurationCollection();

        $this->assertSame('itb_lfc_collection_range_interval', $collection->getApiAlias());
    }

    public function testGetExpectedClass(): void
    {
        $collection = new RangeIntervalListingFilterConfigurationCollection();

        $reflectionClass = new \ReflectionClass(RangeIntervalListingFilterConfigurationCollection::class);
        $method = $reflectionClass->getMethod('getExpectedClass');

        $this->assertSame(RangeIntervalListingFilterConfigurationEntity::class, $method->invoke($collection));
    }
}
