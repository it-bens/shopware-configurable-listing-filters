<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationTranslationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationTranslationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RangeIntervalListingFilterConfigurationTranslationCollection::class)]
final class RangeIntervalListingFilterConfigurationTranslationCollectionTest extends TestCase
{
    public function testGetApiAlias(): void
    {
        $collection = new RangeIntervalListingFilterConfigurationTranslationCollection();

        $this->assertSame('itb_lfc_translation_collection_range_interval', $collection->getApiAlias());
    }

    public function testGetExpectedClass(): void
    {
        $collection = new RangeIntervalListingFilterConfigurationTranslationCollection();

        $reflectionClass = new \ReflectionClass(RangeIntervalListingFilterConfigurationTranslationCollection::class);
        $method = $reflectionClass->getMethod('getExpectedClass');

        $this->assertSame(RangeIntervalListingFilterConfigurationTranslationEntity::class, $method->invoke($collection));
    }
}
