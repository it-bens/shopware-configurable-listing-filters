<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate\CheckboxListingFilterConfigurationTranslationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\Aggregate\CheckboxListingFilterConfigurationTranslationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CheckboxListingFilterConfigurationTranslationCollection::class)]
final class CheckboxListingFilterConfigurationTranslationCollectionTest extends TestCase
{
    public function testGetApiAlias(): void
    {
        $collection = new CheckboxListingFilterConfigurationTranslationCollection();

        $this->assertSame('itb_lfc_translation_collection_checkbox', $collection->getApiAlias());
    }

    public function testGetExpectedClass(): void
    {
        $collection = new CheckboxListingFilterConfigurationTranslationCollection();

        $reflection = new \ReflectionClass(CheckboxListingFilterConfigurationTranslationCollection::class);
        $method = $reflection->getMethod('getExpectedClass');
        $method->setAccessible(true);

        $this->assertSame(CheckboxListingFilterConfigurationTranslationEntity::class, $method->invoke($collection));
    }
}
