<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\Aggregate\RangeIntervalListingFilterConfigurationIntervalTranslationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\Aggregate\RangeIntervalListingFilterConfigurationIntervalTranslationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RangeIntervalListingFilterConfigurationIntervalTranslationCollection::class)]
final class RangeIntervalListingFilterConfigurationIntervalTranslationCollectionTest extends TestCase
{
    public function testGetApiAlias(): void
    {
        $collection = new RangeIntervalListingFilterConfigurationIntervalTranslationCollection();

        $this->assertSame('itb_lfc_translation_collection_range_interval_interval', $collection->getApiAlias());
    }

    public function testGetExpectedClass(): void
    {
        $collection = new RangeIntervalListingFilterConfigurationIntervalTranslationCollection();

        $reflection = new \ReflectionClass(RangeIntervalListingFilterConfigurationIntervalTranslationCollection::class);
        $method = $reflection->getMethod('getExpectedClass');

        $this->assertSame(RangeIntervalListingFilterConfigurationIntervalTranslationEntity::class, $method->invoke($collection));
    }
}
