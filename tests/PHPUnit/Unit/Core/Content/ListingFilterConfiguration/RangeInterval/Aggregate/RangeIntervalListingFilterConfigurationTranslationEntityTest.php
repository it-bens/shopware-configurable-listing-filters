<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\RangeIntervalListingFilterConfigurationTranslationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RangeIntervalListingFilterConfigurationTranslationEntity::class)]
final class RangeIntervalListingFilterConfigurationTranslationEntityTest extends TestCase
{
    public function testConfigurationAssociation(): void
    {
        $entity = new RangeIntervalListingFilterConfigurationTranslationEntity();
        $configEntity = new RangeIntervalListingFilterConfigurationEntity();

        $entity->setRangeIntervalListingFilterConfiguration($configEntity);

        $this->assertSame($configEntity, $entity->getRangeIntervalListingFilterConfiguration());
    }

    public function testGetterAndSetterMethods(): void
    {
        $entity = new RangeIntervalListingFilterConfigurationTranslationEntity();

        $elementPrefix = '€';
        $elementSuffix = ',-';
        $configId = 'test-config-id';

        $entity->setElementPrefix($elementPrefix);
        $entity->setElementSuffix($elementSuffix);
        $entity->setRangeIntervalListingFilterConfigurationId($configId);

        $this->assertSame($elementPrefix, $entity->getElementPrefix());
        $this->assertSame($elementSuffix, $entity->getElementSuffix());
        $this->assertSame($configId, $entity->getRangeIntervalListingFilterConfigurationId());
    }

    public function testInheritanceFromListingFilterConfigurationTranslationEntity(): void
    {
        $entity = new RangeIntervalListingFilterConfigurationTranslationEntity();

        $displayName = 'Test Display Name';
        $entity->setDisplayName($displayName);

        $this->assertSame($displayName, $entity->getDisplayName());
    }

    public function testSetNullElementPrefixAndSuffix(): void
    {
        $entity = new RangeIntervalListingFilterConfigurationTranslationEntity();

        $entity->setElementPrefix(null);
        $entity->setElementSuffix(null);

        $this->assertNull($entity->getElementPrefix());
        $this->assertNull($entity->getElementSuffix());
    }
}
