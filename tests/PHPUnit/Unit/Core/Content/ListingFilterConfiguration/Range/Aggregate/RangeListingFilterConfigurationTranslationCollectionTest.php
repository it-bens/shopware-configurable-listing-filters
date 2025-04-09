<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Range\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\Aggregate\RangeListingFilterConfigurationTranslationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\Aggregate\RangeListingFilterConfigurationTranslationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RangeListingFilterConfigurationTranslationCollection::class)]
final class RangeListingFilterConfigurationTranslationCollectionTest extends TestCase
{
    public function testGetApiAlias(): void
    {
        $collection = new RangeListingFilterConfigurationTranslationCollection();

        $this->assertSame('itb_listing_filter_configuration_translation_collection_range', $collection->getApiAlias());
    }

    public function testGetExpectedClass(): void
    {
        $collection = new RangeListingFilterConfigurationTranslationCollection();

        $reflection = new \ReflectionClass(RangeListingFilterConfigurationTranslationCollection::class);
        $method = $reflection->getMethod('getExpectedClass');
        $method->setAccessible(true);

        $this->assertSame(RangeListingFilterConfigurationTranslationEntity::class, $method->invoke($collection));
    }
}
