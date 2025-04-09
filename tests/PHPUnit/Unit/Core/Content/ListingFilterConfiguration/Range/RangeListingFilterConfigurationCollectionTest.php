<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Range;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RangeListingFilterConfigurationCollection::class)]
final class RangeListingFilterConfigurationCollectionTest extends TestCase
{
    public function testGetApiAlias(): void
    {
        $collection = new RangeListingFilterConfigurationCollection();

        $this->assertSame('itb_listing_filter_configuration_collection_range', $collection->getApiAlias());
    }

    public function testGetExpectedClass(): void
    {
        $collection = new RangeListingFilterConfigurationCollection();

        $reflection = new \ReflectionClass(RangeListingFilterConfigurationCollection::class);
        $method = $reflection->getMethod('getExpectedClass');
        $method->setAccessible(true);

        $this->assertSame(RangeListingFilterConfigurationEntity::class, $method->invoke($collection));
    }
}
