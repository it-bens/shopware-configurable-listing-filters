<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Checkbox\CheckboxListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListingFilterConfigurationCollection::class)]
final class ListingFilterConfigurationCollectionTest extends TestCase
{
    public function testGetIterator(): void
    {
        $checkboxCollection = new CheckboxListingFilterConfigurationCollection();
        $multiSelectCollection = new MultiSelectListingFilterConfigurationCollection();
        $rangeCollection = new RangeListingFilterConfigurationCollection();

        $collection = new ListingFilterConfigurationCollection($checkboxCollection, $multiSelectCollection, $rangeCollection);

        $iterator = $collection->getIterator();
        $this->assertInstanceOf(\ArrayIterator::class, $iterator);
        $this->assertCount(0, iterator_to_array($iterator));
    }

    public function testSortFilterConfigurations(): void
    {
        $checkboxCollection = new CheckboxListingFilterConfigurationCollection();
        $multiSelectCollection = new MultiSelectListingFilterConfigurationCollection();
        $rangeCollection = new RangeListingFilterConfigurationCollection();

        $checkboxEntity = new CheckboxListingFilterConfigurationEntity();
        $checkboxEntity->setUniqueIdentifier('checkbox');
        $checkboxEntity->setDalField('checkboxField');
        $checkboxEntity->setPosition(2);

        $multiSelectEntity = new MultiSelectListingFilterConfigurationEntity();
        $multiSelectEntity->setUniqueIdentifier('multiSelect');
        $multiSelectEntity->setDalField('multiSelectField');
        $multiSelectEntity->setPosition(1);

        $rangeEntity = new RangeListingFilterConfigurationEntity();
        $rangeEntity->setUniqueIdentifier('range');
        $rangeEntity->setDalField('rangeField');
        $rangeEntity->setPosition(3);

        $checkboxCollection->add($checkboxEntity);
        $multiSelectCollection->add($multiSelectEntity);
        $rangeCollection->add($rangeEntity);

        $collection = new ListingFilterConfigurationCollection($checkboxCollection, $multiSelectCollection, $rangeCollection);

        $result = $collection->getListingFilterConfigurations();

        $this->assertCount(3, $result);
        $this->assertEquals($multiSelectEntity, $result[0]);
        $this->assertEquals($checkboxEntity, $result[1]);
        $this->assertEquals($rangeEntity, $result[2]);
    }

    public function testSortFilterConfigurationsSamePosition(): void
    {
        $checkboxCollection = new CheckboxListingFilterConfigurationCollection();
        $multiSelectCollection = new MultiSelectListingFilterConfigurationCollection();
        $rangeCollection = new RangeListingFilterConfigurationCollection();

        $checkboxEntity = new CheckboxListingFilterConfigurationEntity();
        $checkboxEntity->setUniqueIdentifier('bField');
        $checkboxEntity->setDalField('bField');
        $checkboxEntity->setPosition(1);

        $multiSelectEntity = new MultiSelectListingFilterConfigurationEntity();
        $multiSelectEntity->setUniqueIdentifier('aField');
        $multiSelectEntity->setDalField('aField');
        $multiSelectEntity->setPosition(1);

        $checkboxCollection->add($checkboxEntity);
        $multiSelectCollection->add($multiSelectEntity);

        $collection = new ListingFilterConfigurationCollection($checkboxCollection, $multiSelectCollection, $rangeCollection);

        $result = $collection->getListingFilterConfigurations();

        $this->assertCount(2, $result);
        $this->assertEquals($multiSelectEntity, $result[0]);
        $this->assertEquals($checkboxEntity, $result[1]);
    }

    public function testSortFilterConfigurationsWithNullPositions(): void
    {
        $checkboxCollection = new CheckboxListingFilterConfigurationCollection();
        $multiSelectCollection = new MultiSelectListingFilterConfigurationCollection();
        $rangeCollection = new RangeListingFilterConfigurationCollection();

        $checkboxEntity = new CheckboxListingFilterConfigurationEntity();
        $checkboxEntity->setUniqueIdentifier('checkboxField');
        $checkboxEntity->setDalField('checkboxField');
        $checkboxEntity->setPosition(null);

        $multiSelectEntity = new MultiSelectListingFilterConfigurationEntity();
        $multiSelectEntity->setUniqueIdentifier('multiSelectField');
        $multiSelectEntity->setDalField('multiSelectField');
        $multiSelectEntity->setPosition(1);

        $rangeEntity = new RangeListingFilterConfigurationEntity();
        $rangeEntity->setUniqueIdentifier('rangeField');
        $rangeEntity->setDalField('rangeField');
        $rangeEntity->setPosition(null);

        $checkboxCollection->add($checkboxEntity);
        $multiSelectCollection->add($multiSelectEntity);
        $rangeCollection->add($rangeEntity);

        $collection = new ListingFilterConfigurationCollection($checkboxCollection, $multiSelectCollection, $rangeCollection);

        $result = $collection->getListingFilterConfigurations();

        $this->assertCount(3, $result);
        $this->assertEquals($multiSelectEntity, $result[0]);
    }
}
