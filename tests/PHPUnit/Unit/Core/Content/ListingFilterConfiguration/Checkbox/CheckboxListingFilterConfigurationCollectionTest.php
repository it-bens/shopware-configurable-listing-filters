<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Checkbox;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CheckboxListingFilterConfigurationCollection::class)]
final class CheckboxListingFilterConfigurationCollectionTest extends TestCase
{
    public function testGetApiAlias(): void
    {
        $collection = new CheckboxListingFilterConfigurationCollection();

        $this->assertSame('itb_lfc_collection_checkbox', $collection->getApiAlias());
    }

    public function testGetExpectedClass(): void
    {
        $collection = new CheckboxListingFilterConfigurationCollection();

        $reflection = new \ReflectionClass(CheckboxListingFilterConfigurationCollection::class);
        $method = $reflection->getMethod('getExpectedClass');
        $method->setAccessible(true);

        $this->assertSame(CheckboxListingFilterConfigurationEntity::class, $method->invoke($collection));
    }
}
